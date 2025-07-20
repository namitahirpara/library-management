<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = auth()->user()->borrowings()->with('book')->orderBy('created_at', 'desc')->paginate(10);
        return view('borrowings.index', compact('borrowings'));
    }

    public function borrow(Book $book)
    {
        if (!$book->canBeBorrowed()) {
            return redirect()->back()->with('error', 'This book is not available for borrowing.');
        }

        // Check if user already has this book borrowed
        $existingBorrowing = auth()->user()->borrowings()
            ->where('book_id', $book->id)
            ->where('status', 'borrowed')
            ->first();

        if ($existingBorrowing) {
            return redirect()->back()->with('error', 'You have already borrowed this book.');
        }

        // Create borrowing record
        $borrowing = Borrowing::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'borrowed_date' => now(),
            'due_date' => now()->addDays(14), // 2 weeks loan period
            'status' => 'borrowed',
        ]);

        // Update book availability
        $book->decrement('available_quantity');

        return redirect()->route('borrowings.index')->with('success', 'Book borrowed successfully! Due date: ' . $borrowing->due_date->format('M d, Y'));
    }

    public function return(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== auth()->id() && !auth()->user()->isAdmin() && !auth()->user()->isLibrarian()) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        if ($borrowing->status === 'returned') {
            return redirect()->back()->with('error', 'This book has already been returned.');
        }

        // Update borrowing record
        $borrowing->update([
            'returned_date' => now(),
            'status' => 'returned',
        ]);

        // Update book availability
        $borrowing->book->increment('available_quantity');

        return redirect()->route('borrowings.index')->with('success', 'Book returned successfully!');
    }

    public function adminIndex()
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isLibrarian()) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $borrowings = Borrowing::with(['user', 'book'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.borrowings.index', compact('borrowings'));
    }

    public function overdue()
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isLibrarian()) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $overdueBorrowings = Borrowing::with(['user', 'book'])
            ->where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->orderBy('due_date', 'asc')
            ->paginate(15);

        return view('admin.borrowings.overdue', compact('overdueBorrowings'));
    }
} 