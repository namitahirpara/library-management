@extends('layouts.app')

@section('title', 'Admin Dashboard - Library Management System')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">
            <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
        </h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-book fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Total Books</h5>
                <h2 class="text-primary">{{ $totalBooks }}</h2>
                <p class="card-text">Books in library</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-users fa-3x text-success mb-3"></i>
                <h5 class="card-title">Total Users</h5>
                <h2 class="text-success">{{ $totalUsers }}</h2>
                <p class="card-text">Registered users</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-exchange-alt fa-3x text-info mb-3"></i>
                <h5 class="card-title">Total Borrowings</h5>
                <h2 class="text-info">{{ $totalBorrowings }}</h2>
                <p class="card-text">All time borrowings</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h5 class="card-title">Overdue Books</h5>
                <h2 class="text-warning">{{ $overdueBorrowings }}</h2>
                <p class="card-text">Books overdue</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Recent Borrowings
                </h5>
            </div>
            <div class="card-body">
                @if($recentBorrowings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Book</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBorrowings as $borrowing)
                                <tr>
                                    <td>{{ $borrowing->user->name }}</td>
                                    <td>{{ $borrowing->book->title }}</td>
                                    <td>{{ $borrowing->created_at->format('M d, Y') }}</td>
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
                @else
                    <p class="text-muted text-center mb-0">No recent borrowings.</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-star me-2"></i>Popular Books
                </h5>
            </div>
            <div class="card-body">
                @if($popularBooks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Book</th>
                                    <th>Author</th>
                                    <th>Borrowings</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($popularBooks as $book)
                                <tr>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->author }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $book->borrowings_count }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No borrowing data available.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 