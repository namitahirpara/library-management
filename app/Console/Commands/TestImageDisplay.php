<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;

class TestImageDisplay extends Command
{
    protected $signature = 'books:test-display';
    protected $description = 'Test image display logic and URL generation';

    public function handle()
    {
        $this->info('Testing image display logic...');
        
        $books = Book::whereNotNull('cover_image')->get();
        
        foreach ($books as $book) {
            $this->line("Book: {$book->title}");
            $this->line("  Cover Image: {$book->cover_image}");
            $this->line("  Has Real Image: " . ($book->hasRealCoverImage() ? 'Yes' : 'No'));
            $this->line("  Cover Image URL: {$book->cover_image_url}");
            
            $extension = strtolower(pathinfo($book->cover_image, PATHINFO_EXTENSION));
            $this->line("  Extension: {$extension}");
            $this->line("  Is SVG: " . ($extension === 'svg' ? 'Yes' : 'No'));
            
            $this->newLine();
        }
    }
} 