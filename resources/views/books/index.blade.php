@extends('layouts.app')

@section('title', 'Books - Library Management System')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2>
            <i class="fas fa-books me-2"></i>Books
        </h2>
    </div>
    <div class="col-md-4 text-end">
        @if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())
            <a href="{{ route('books.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Book
            </a>
        @endif
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <form action="{{ route('books.search') }}" method="GET" class="d-flex" id="searchForm">
            <input type="text" name="query" class="form-control me-2" 
                   placeholder="Search books by title, author, ISBN, or category..." 
                   value="{{ $query ?? '' }}">
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('searchForm').addEventListener('submit', function(e) {
    console.log('Search form submitted');
    console.log('Form action:', this.action);
    console.log('Form method:', this.method);
    console.log('Query value:', this.querySelector('input[name="query"]').value);
});
</script>

@if(isset($query))
<div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-search me-2"></i>
            Search results for: <strong>"{{ $query }}"</strong>
            <a href="{{ route('books.index') }}" class="float-end text-decoration-none">
                <i class="fas fa-times me-1"></i>Clear search
            </a>
        </div>
    </div>
</div>
@endif

@if($books->count() > 0)
<div class="row">
    @foreach($books as $book)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
        <div class="book-cover-container" style="height: 250px; overflow: hidden; position: relative;">
    @if($book->cover_image_url)
        @php
            $extension = strtolower(pathinfo($book->cover_image, PATHINFO_EXTENSION));
            $isSvg = $extension === 'svg';
        @endphp

        @if($isSvg)
            {{-- SVG Handling --}}
            <object data="{{ $book->cover_image_url }}"
                    type="image/svg+xml"
                    class="card-img-top book-cover-image"
                    style="height: 100%; width: 100%; object-fit: cover; transition: transform 0.3s ease;"
                    onmouseover="this.style.transform='scale(1.05)'"
                    onmouseout="this.style.transform='scale(1)'">
                {{-- Fallback if SVG fails --}}
                <div class="book-cover-placeholder"
                     style="height: 100%; width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); position: absolute; top: 0; left: 0;">
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <div class="text-center text-white">
                            <i class="fas fa-book fa-4x mb-3"></i>
                            <h6 class="mb-0">{{ $book->title }}</h6>
                            <small>by {{ $book->author }}</small>
                        </div>
                    </div>
                </div>
            </object>
        @else
            {{-- PNG, JPG, JPEG Handling --}}
            <img src="{{ $book->cover_image_url }}"
                 class="card-img-top book-cover-image"
                 alt="{{ $book->title }}"
                 style="height: 100%; width: 100%; object-fit: cover; transition: transform 0.3s ease;"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                 onmouseover="this.style.transform='scale(1.05)'"
                 onmouseout="this.style.transform='scale(1)'">
            <div class="book-cover-placeholder"
                 style="height: 100%; width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: none; position: absolute; top: 0; left: 0;">
                <div class="d-flex align-items-center justify-content-center h-100">
                    <div class="text-center text-white">
                        <i class="fas fa-book fa-4x mb-3"></i>
                        <h6 class="mb-0">{{ $book->title }}</h6>
                        <small>by {{ $book->author }}</small>
                    </div>
                </div>
            </div>
        @endif
    @else
        {{-- Placeholder if no image exists --}}
        <div class="book-cover-placeholder"
             style="height: 100%; width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="d-flex align-items-center justify-content-center h-100">
                <div class="text-center text-white">
                    <i class="fas fa-book fa-4x mb-3"></i>
                    <h6 class="mb-0">{{ $book->title }}</h6>
                    <small>by {{ $book->author }}</small>
                </div>
            </div>
        </div>
    @endif
</div>

            
            <div class="card-body">
                <h5 class="card-title">{{ $book->title }}</h5>
                <p class="card-text text-muted">by {{ $book->author }}</p>
                
                @if(config('app.debug'))
                    <div class="mb-2">
                        @if($book->hasRealCoverImage())
                            <span class="badge bg-success">Real Image</span>
                        @else
                            <span class="badge bg-secondary">Placeholder</span>
                        @endif
                    </div>
                @endif
                
                <div class="mb-2">
                    <span class="badge bg-secondary">{{ $book->category }}</span>
                    @if($book->isAvailable())
                        <span class="badge bg-success">Available</span>
                    @else
                        <span class="badge bg-danger">Unavailable</span>
                    @endif
                </div>
                
                <p class="card-text small">
                    <strong>ISBN:</strong> {{ $book->isbn }}<br>
                    <strong>Available:</strong> {{ $book->available_quantity }}/{{ $book->quantity }}<br>
                    @if($book->publication_year)
                        <strong>Published:</strong> {{ $book->publication_year }}
                    @endif
                </p>
                
                @if($book->description)
                    <p class="card-text">{{ Str::limit($book->description, 100) }}</p>
                @endif
            </div>
            
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye me-1"></i>View Details
                    </a>
                    
                    @if($book->canBeBorrowed())
                        <form action="{{ route('books.borrow', $book) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fas fa-plus me-1"></i>Borrow
                            </button>
                        </form>
                    @else
                        <button class="btn btn-sm btn-secondary" disabled>
                            <i class="fas fa-times me-1"></i>Unavailable
                        </button>
                    @endif
                </div>
                
                @if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())
                    <div class="mt-2 d-flex justify-content-between">
                        <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Are you sure you want to delete this book?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-center">
            {{ $books->links() }}
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No books found</h5>
                <p class="text-muted">
                    @if(isset($query))
                        No books match your search criteria.
                    @else
                        No books are available in the library yet.
                    @endif
                </p>
                @if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())
                    <a href="{{ route('books.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add First Book
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection 