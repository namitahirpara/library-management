@extends('layouts.app')

@section('title', 'Profile - Library Management System')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-user-circle fa-5x text-primary"></i>
                </div>
                <h4 class="card-title">{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                <span class="badge bg-secondary">{{ ucfirst($user->role->name ?? 'No Role') }}</span>
                
                <div class="mt-3">
                    <a href="{{ route('users.edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Contact Information
                </h6>
            </div>
            <div class="card-body">
                @if($user->phone)
                    <p class="mb-2">
                        <i class="fas fa-phone me-2 text-muted"></i>
                        {{ $user->phone }}
                    </p>
                @endif
                
                @if($user->address)
                    <p class="mb-0">
                        <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                        {{ $user->address }}
                    </p>
                @endif
                
                @if(!$user->phone && !$user->address)
                    <p class="text-muted mb-0">No contact information available.</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Borrowing History
                </h5>
            </div>
            <div class="card-body">
                @if($borrowings->count() > 0)
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
                        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No borrowing history</h6>
                        <p class="text-muted">You haven't borrowed any books yet.</p>
                        <a href="{{ route('books.index') }}" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Browse Books
                        </a>
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