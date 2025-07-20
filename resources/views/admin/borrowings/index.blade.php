@extends('layouts.app')

@section('title', 'All Borrowings - Library Management System')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">
            <i class="fas fa-list me-2"></i>All Borrowings
        </h2>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-exchange-alt me-2"></i>Borrowing Records
                </h5>
            </div>
            <div class="card-body">
                @if($borrowings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Book</th>
                                    <th>Borrowed Date</th>
                                    <th>Due Date</th>
                                    <th>Returned Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($borrowings as $borrowing)
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
                                        @if($borrowing->isOverdue())
                                            <span class="text-danger fw-bold">{{ $borrowing->due_date->format('M d, Y') }}</span>
                                        @else
                                            {{ $borrowing->due_date->format('M d, Y') }}
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
                                                <span class="badge bg-danger">
                                                    Overdue ({{ $borrowing->due_date->diffInDays(now()) }} days)
                                                </span>
                                            @else
                                                <span class="badge bg-success">Active</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($borrowing->status === 'borrowed')
                                            <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-undo me-1"></i>Mark Returned
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $borrowings->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No borrowing records</h6>
                        <p class="text-muted">No books have been borrowed yet.</p>
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