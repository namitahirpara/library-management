<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::paginate(12);
        return view('books.index', compact('books'));
    }

    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isLibrarian()) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
        return view('books.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isLibrarian()) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn|max:20',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048|dimensions:min_width=100,min_height=100',
        ], [
            'cover_image.image' => 'The cover image must be a valid image file.',
            'cover_image.mimes' => 'The cover image must be a file of type: jpeg, png, jpg, gif, webp.',
            'cover_image.max' => 'The cover image may not be greater than 2MB.',
            'cover_image.dimensions' => 'The cover image must be at least 100x100 pixels.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $coverImagePath = null;

        // Handle file upload
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            
            // Generate a unique filename
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            
            // Store the file
            $coverImagePath = $file->storeAs('book-covers', $fileName, 'public');
            
            // Log the upload for debugging
            \Log::info('Book cover uploaded', [
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $coverImagePath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);
        }

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'description' => $request->description,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'available_quantity' => $request->quantity,
            'publisher' => $request->publisher,
            'publication_year' => $request->publication_year,
            'cover_image' => $coverImagePath,
        ]);

        return redirect()->route('books.index')->with('success', 'Book added successfully!');
    }

    public function edit(Book $book)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isLibrarian()) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isLibrarian()) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $book->id . '|max:20',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048|dimensions:min_width=100,min_height=100',
        ], [
            'cover_image.image' => 'The cover image must be a valid image file.',
            'cover_image.mimes' => 'The cover image must be a file of type: jpeg, png, jpg, gif, webp.',
            'cover_image.max' => 'The cover image may not be greater than 2MB.',
            'cover_image.dimensions' => 'The cover image must be at least 100x100 pixels.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $coverImagePath = $book->cover_image;

        // Handle file upload
        if ($request->hasFile('cover_image')) {
            // Delete old file if it exists
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            
            $file = $request->file('cover_image');
            
            // Generate a unique filename
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            
            // Store the file
            $coverImagePath = $file->storeAs('book-covers', $fileName, 'public');
            
            // Log the upload for debugging
            \Log::info('Book cover updated', [
                'book_id' => $book->id,
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $coverImagePath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);
        }

        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'description' => $request->description,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'publisher' => $request->publisher,
            'publication_year' => $request->publication_year,
            'cover_image' => $coverImagePath,
        ]);

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isLibrarian()) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Delete cover image file if it exists
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }

    public function search(Request $request)
    {
        \Log::info('Search method called', [
            'query' => $request->get('query'),
            'all_params' => $request->all(),
            'url' => $request->url(),
            'method' => $request->method()
        ]);
        
        $query = $request->get('query');
        if (!$query) {
            return redirect()->route('books.index');
        }
        
        $books = Book::where('title', 'like', "%{$query}%")
                    ->orWhere('author', 'like', "%{$query}%")
                    ->orWhere('isbn', 'like', "%{$query}%")
                    ->orWhere('category', 'like', "%{$query}%")
                    ->paginate(12);

        \Log::info('Search results', [
            'query' => $query,
            'results_count' => $books->count(),
            'total_count' => $books->total()
        ]);

        return view('books.index', compact('books', 'query'));
    }
} 