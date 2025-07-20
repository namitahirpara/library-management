@extends('layouts.app')

@section('title', 'My Borrowings - Library Management System')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">
            <i class="fas fa-exchange-alt me-2"></i>My Borrowings
        </h2>
    </div>
</div>

@if($borrowings->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Borrowing History
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
                                <th>Returned Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowings as $borrowing)
                            <tr>
                                <td>
                                    <a href="{{ route('books.show', $borrowing->book) }}" class="text-decoration-none">
                                        {{ $borrowing->book->title }}
                                    </a>
                                </td>
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
                                        @if($borrowing->isOverdue())
                                            <span class="text-danger fw-bold">{{ $borrowing->due_date->format('M d, Y') }}</span>
                                        @else
                                            {{ $borrowing->due_date->format('M d, Y') }}
                                        @endif
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
                                        @if($borrowing->due_date && $borrowing->isOverdue())
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
                                                <i class="fas fa-undo me-1"></i>Return
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
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No borrowing history</h5>
                <p class="text-muted">You haven't borrowed any books yet. Start exploring our collection!</p>
                <a href="{{ route('books.index') }}" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Browse Books
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row mt-3">
    <div class="col-12">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>
@endsection 