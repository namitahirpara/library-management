@extends('layouts.app')

@section('title', 'Dashboard - Library Management System')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
        </h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-book fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Current Borrowings</h5>
                <h2 class="text-primary">{{ $currentBorrowings->count() }}</h2>
                <p class="card-text">Books you currently have borrowed</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h5 class="card-title">Overdue Books</h5>
                <h2 class="text-warning">{{ $overdueBorrowings->count() }}</h2>
                <p class="card-text">Books that are overdue</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-history fa-3x text-info mb-3"></i>
                <h5 class="card-title">Total Borrowings</h5>
                <h2 class="text-info">{{ $recentBorrowings->count() }}</h2>
                <p class="card-text">Books borrowed in total</p>
            </div>
        </div>
    </div>
</div>

@if($overdueBorrowings->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Overdue Books
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Due Date</th>
                                <th>Days Overdue</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overdueBorrowings as $borrowing)
                            <tr>
                                <td>{{ $borrowing->book->title }}</td>
                                <td>{{ $borrowing->book->author }}</td>
                                <td>
                                    @if($borrowing->due_date)
                                        {{ $borrowing->due_date->format('M d, Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($borrowing->due_date)
                                        <span class="badge bg-danger">
                                            {{ $borrowing->due_date->diffInDays(now()) }} days
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-undo me-1"></i>Return
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($currentBorrowings->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-book me-2"></i>Current Borrowings
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Borrowed Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currentBorrowings as $borrowing)
                            <tr>
                                <td>{{ $borrowing->book->title }}</td>
                                <td>{{ $borrowing->book->author }}</td>
                                <td>
                                    @if($borrowing->borrowed_date)
                                        {{ $borrowing->borrowed_date->format('M d, Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($borrowing->due_date)
                                        {{ $borrowing->due_date->format('M d, Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($borrowing->isOverdue())
                                        <span class="badge bg-danger">Overdue</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-undo me-1"></i>Return
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($recentBorrowings->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Recent Borrowing History
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Borrowed Date</th>
                                <th>Returned Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBorrowings as $borrowing)
                            <tr>
                                <td>{{ $borrowing->book->title }}</td>
                                <td>{{ $borrowing->book->author }}</td>
                                <td>
                                    @if($borrowing->borrowed_date)
                                        {{ $borrowing->borrowed_date->format('M d, Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($borrowing->returned_date)
                                        {{ $borrowing->returned_date->format('M d, Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($borrowing->status === 'returned')
                                        <span class="badge bg-secondary">Returned</span>
                                    @elseif($borrowing->status === 'borrowed')
                                        @if($borrowing->isOverdue())
                                            <span class="badge bg-danger">Overdue</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($currentBorrowings->count() === 0 && $recentBorrowings->count() === 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No borrowing history yet</h5>
                <p class="text-muted">Start exploring our book collection and borrow your first book!</p>
                <a href="{{ route('books.index') }}" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Browse Books
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection 