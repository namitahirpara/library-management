@extends('layouts.app')

@section('title', $book->title . ' - Library Management System')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            @if($book->cover_image_url)
                @if(str_contains($book->cover_image, '.svg'))
                    <object data="{{ $book->cover_image_url }}" 
                            type="image/svg+xml" 
                            class="card-img-top" 
                            style="height: 400px; width: 100%; object-fit: cover;">
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="fas fa-book fa-5x text-muted"></i>
                        </div>
                    </object>
                @else
                    <img src="{{ $book->cover_image_url }}" class="card-img-top" alt="{{ $book->title }}" style="height: 400px; width: 100%; object-fit: cover;">
                @endif
            @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="fas fa-book fa-5x text-muted"></i>
                </div>
            @endif
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">{{ $book->title }}</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Author:</strong></div>
                    <div class="col-sm-9">{{ $book->author }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>ISBN:</strong></div>
                    <div class="col-sm-9">{{ $book->isbn }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Category:</strong></div>
                    <div class="col-sm-9">
                        <span class="badge bg-secondary">{{ $book->category }}</span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Availability:</strong></div>
                    <div class="col-sm-9">
                        @if($book->isAvailable())
                            <span class="badge bg-success">Available ({{ $book->available_quantity }}/{{ $book->quantity }})</span>
                        @else
                            <span class="badge bg-danger">Unavailable ({{ $book->available_quantity }}/{{ $book->quantity }})</span>
                        @endif
                    </div>
                </div>
                
                @if($book->publisher)
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Publisher:</strong></div>
                    <div class="col-sm-9">{{ $book->publisher }}</div>
                </div>
                @endif
                
                @if($book->publication_year)
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Published:</strong></div>
                    <div class="col-sm-9">{{ $book->publication_year }}</div>
                </div>
                @endif
                
                @if($book->description)
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Description:</strong></div>
                    <div class="col-sm-9">{{ $book->description }}</div>
                </div>
                @endif
                
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            @if($book->canBeBorrowed())
                                <form action="{{ route('books.borrow', $book) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-plus me-2"></i>Borrow Book
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-times me-2"></i>Unavailable
                                </button>
                            @endif
                            
                            @if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>Edit Book
                                </a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this book?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash me-2"></i>Delete Book
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Borrowing History
                </h5>
            </div>
            <div class="card-body">
                @if($book->borrowings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Borrower</th>
                                    <th>Borrowed Date</th>
                                    <th>Due Date</th>
                                    <th>Returned Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($book->borrowings->take(10) as $borrowing)
                                <tr>
                                    <td>{{ $borrowing->user->name }}</td>
                                    <td>{{ $borrowing->borrowed_date->format('M d, Y') }}</td>
                                    <td>{{ $borrowing->due_date->format('M d, Y') }}</td>
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
                @else
                    <p class="text-muted text-center mb-0">No borrowing history for this book.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <a href="{{ route('books.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Books
        </a>
    </div>
</div>
@endsection 