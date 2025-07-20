<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearBookPlaceholders extends Command
{
    protected $signature = 'books:clear-placeholders';
    protected $description = 'Clear generated SVG placeholder images from books';

    public function handle()
    {
        $books = Book::whereNotNull('cover_image')->get();
        
        if ($books->isEmpty()) {
            $this->info('No books with cover images found.');
            return;
        }

        $this->info("Found {$books->count()} books with cover images.");
        
        $clearedCount = 0;
        foreach ($books as $book) {
            // Check if it's a generated SVG placeholder
            if (str_contains($book->cover_image, '.svg')) {
                // Delete the SVG file
                if (Storage::disk('public')->exists($book->cover_image)) {
                    Storage::disk('public')->delete($book->cover_image);
                }
                
                // Clear the cover_image field
                $book->update(['cover_image' => null]);
                $clearedCount++;
                
                $this->line("Cleared placeholder for: {$book->title}");
            }
        }
        
        if ($clearedCount > 0) {
            $this->info("Cleared {$clearedCount} placeholder images.");
        } else {
            $this->info("No placeholder images found to clear.");
        }
    }
}
