<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Temporary test route for debugging storage URLs
Route::get('/test-storage', function () {
    $testFile = 'book-covers/1752669826_1726578600_room-1.jpg';
    $exists = Storage::disk('public')->exists($testFile);
    $url = Storage::disk('public')->url($testFile);
    
    return [
        'file_exists' => $exists,
        'file_path' => $testFile,
        'generated_url' => $url,
        'app_url' => config('app.url'),
        'storage_url' => config('filesystems.disks.public.url')
    ];
});

// Temporary test route for search debugging
Route::get('/test-search', function () {
    return [
        'message' => 'Search test route works',
        'books_search_route' => route('books.search'),
        'books_index_route' => route('books.index')
    ];
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password reset routes (OTP-based)
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('password.email');
Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('password.verify');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('password.verify.submit');
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Test search route outside auth middleware
Route::get('/books/search', [BookController::class, 'search'])->name('books.search');


// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Books
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->where('book', '[0-9]+')->name('books.show');
    
    // Borrowings
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::post('/books/{book}/borrow', [BorrowingController::class, 'borrow'])->name('books.borrow');
    Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'return'])->name('borrowings.return');
    
    // User profile
    Route::get('/profile', [UserController::class, 'show'])->name('users.profile');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('users.update');
    Route::post('/profile/change-password', [UserController::class, 'changePassword'])->name('users.change-password');
    
    // Admin routes
    Route::middleware('role:admin,librarian')->group(function () {
        // Book management
        Route::get('/admin/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/admin/books', [BookController::class, 'store'])->name('books.store');
        Route::get('/admin/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/admin/books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/admin/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
        
        // Borrowing management
        Route::get('/admin/borrowings', [BorrowingController::class, 'adminIndex'])->name('admin.borrowings.index');
        Route::get('/admin/borrowings/overdue', [BorrowingController::class, 'overdue'])->name('admin.borrowings.overdue');
    });
    
    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        // User management
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/{user}', [UserController::class, 'showUser'])->name('admin.users.show');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });
});
