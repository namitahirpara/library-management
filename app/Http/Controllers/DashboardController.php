<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin() || $user->isLibrarian()) {
            // Admin/Librarian Dashboard
            $totalBooks = Book::count();
            $totalUsers = User::count();
            $totalBorrowings = Borrowing::count();
            $overdueBorrowings = Borrowing::where('status', 'borrowed')
                ->where('due_date', '<', now())
                ->count();
            
            $recentBorrowings = Borrowing::with(['user', 'book'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            $popularBooks = Book::withCount('borrowings')
                ->orderBy('borrowings_count', 'desc')
                ->limit(5)
                ->get();
            
            return view('admin.dashboard', compact(
                'totalBooks',
                'totalUsers',
                'totalBorrowings',
                'overdueBorrowings',
                'recentBorrowings',
                'popularBooks'
            ));
        } else {
            // Student Dashboard
            $currentBorrowings = $user->borrowings()
                ->with('book')
                ->where('status', 'borrowed')
                ->orderBy('due_date', 'asc')
                ->get();
            
            $overdueBorrowings = $currentBorrowings->filter(function ($borrowing) {
                return $borrowing->isOverdue();
            });
            
            $recentBorrowings = $user->borrowings()
                ->with('book')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            return view('dashboard', compact(
                'currentBorrowings',
                'overdueBorrowings',
                'recentBorrowings'
            ));
        }
    }
} 