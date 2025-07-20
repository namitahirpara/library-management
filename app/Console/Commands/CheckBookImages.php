<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;

class CheckBookImages extends Command
{
    protected $signature = 'books:check-images';
    protected $description = 'Check and display information about book cover images';

    public function handle()
    {
        $this->info('Checking book cover images...');
        
        $books = Book::whereNotNull('cover_image')->get();
        
        if ($books->isEmpty()) {
            $this->info('No books with cover images found.');
            return;
        }

        $this->info("Found {$books->count()} books with cover images:");
        
        $realImages = 0;
        $svgPlaceholders = 0;
        
        foreach ($books as $book) {
            $extension = strtolower(pathinfo($book->cover_image, PATHINFO_EXTENSION));
            $isSvg = $extension === 'svg';
            
            if ($isSvg) {
                $svgPlaceholders++;
                $status = 'SVG Placeholder';
                $icon = '🖼️';
            } else {
                $realImages++;
                $status = 'Real Image';
                $icon = '📷';
            }
            
            $this->line("  {$icon} {$book->title} - {$book->cover_image} ({$status})");
            
            if (!$isSvg) {
                $this->line("    URL: {$book->cover_image_url}");
            }
        }
        
        $this->newLine();
        $this->info("Summary:");
        $this->line("  Real Images (JPG/PNG/JPEG): {$realImages}");
        $this->line("  SVG Placeholders: {$svgPlaceholders}");
        
        if ($realImages === 0) {
            $this->warn('No real images found! All books have SVG placeholders.');
            $this->info('To upload real images, edit books and upload JPG/PNG/JPEG files.');
        }
    }
} 