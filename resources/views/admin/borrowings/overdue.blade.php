@extends('layouts.app')

@section('title', 'Overdue Books - Library Management System')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">
            <i class="fas fa-exclamation-triangle me-2"></i>Overdue Books
        </h2>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Overdue Borrowings
                </h5>
            </div>
            <div class="card-body">
                @if($overdueBorrowings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Book</th>
                                    <th>Borrowed Date</th>
                                    <th>Due Date</th>
                                    <th>Days Overdue</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($overdueBorrowings as $borrowing)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-circle fa-2x text-primary me-2"></i>
                                            <div>
                                                <strong>{{ $borrowing->user->name }}</strong><br>
                                                <small class="text-muted">{{ $borrowing->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $borrowing->book->title }}</strong><br>
                                            <small class="text-muted">by {{ $borrowing->book->author }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $borrowing->borrowed_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="text-danger fw-bold">{{ $borrowing->due_date->format('M d, Y') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ $borrowing->due_date->diffInDays(now()) }} days
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-undo me-1"></i>Mark Returned
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $overdueBorrowings->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h6 class="text-success">No overdue books!</h6>
                        <p class="text-muted">All books have been returned on time.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>
@endsection 