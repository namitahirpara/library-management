<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateBookCovers extends Command
{
    protected $signature = 'books:generate-covers';
    protected $description = 'Generate placeholder cover images for books without covers';

    public function handle()
    {
        $books = Book::whereNull('cover_image')->get();
        
        if ($books->isEmpty()) {
            $this->info('All books already have cover images!');
            return;
        }

        $this->info("Generating cover images for {$books->count()} books...");

        foreach ($books as $book) {
            $this->generateCoverImage($book);
            $this->line("Generated cover for: {$book->title}");
        }

        $this->info('Cover images generated successfully!');
    }

    private function generateCoverImage(Book $book)
    {
        // Create SVG placeholder
        $svg = $this->createSVGCover($book);
        
        // Save SVG file
        $filename = 'book-covers/' . time() . '_' . str_replace([' ', '/', '\\'], '_', $book->title) . '.svg';
        
        // Ensure directory exists
        Storage::disk('public')->makeDirectory('book-covers');
        
        // Save SVG
        Storage::disk('public')->put($filename, $svg);
        
        // Update book record
        $book->update(['cover_image' => $filename]);
    }

    private function createSVGCover(Book $book)
    {
        $width = 300;
        $height = 400;
        
        // Generate a color based on the book title
        $hash = crc32($book->title);
        $hue = $hash % 360;
        $saturation = 60 + ($hash % 20);
        $lightness = 40 + ($hash % 20);
        
        $primaryColor = "hsl({$hue}, {$saturation}%, {$lightness}%)";
        $secondaryColor = "hsl(" . (($hue + 30) % 360) . ", {$saturation}%, " . ($lightness + 10) . "%)";
        
        // Wrap title text
        $title = $this->wrapText($book->title, 20);
        $titleLines = explode("\n", $title);
        
        // Create SVG
        $svg = '<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">';
        
        // Background gradient
        $svg .= '<defs>';
        $svg .= '<linearGradient id="bgGradient" x1="0%" y1="0%" x2="0%" y2="100%">';
        $svg .= '<stop offset="0%" style="stop-color:' . $primaryColor . ';stop-opacity:1" />';
        $svg .= '<stop offset="100%" style="stop-color:' . $secondaryColor . ';stop-opacity:1" />';
        $svg .= '</linearGradient>';
        $svg .= '</defs>';
        
        // Background
        $svg .= '<rect width="' . $width . '" height="' . $height . '" fill="url(#bgGradient)"/>';
        
        // Book icon
        $iconX = $width / 2 - 30;
        $iconY = $height / 2 - 60;
        
        // Book spine
        $svg .= '<rect x="' . ($iconX + 45) . '" y="' . $iconY . '" width="10" height="80" fill="white" opacity="0.9"/>';
        
        // Book cover
        $svg .= '<rect x="' . $iconX . '" y="' . $iconY . '" width="45" height="80" fill="white" opacity="0.8"/>';
        
        // Book pages
        $svg .= '<rect x="' . ($iconX + 5) . '" y="' . ($iconY + 5) . '" width="35" height="70" fill="white"/>';
        
        // Title text
        $textY = $iconY + 80 + 20;
        foreach ($titleLines as $line) {
            $svg .= '<text x="' . ($width / 2) . '" y="' . $textY . '" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="14" font-weight="bold">' . htmlspecialchars($line) . '</text>';
            $textY += 20;
        }
        
        // Author text
        $author = "by " . $book->author;
        $svg .= '<text x="' . ($width / 2) . '" y="' . ($textY + 10) . '" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="12" opacity="0.9">' . htmlspecialchars($author) . '</text>';
        
        // Decorative elements
        $svg .= '<circle cx="' . ($width / 2) . '" cy="' . ($iconY - 20) . '" r="3" fill="white" opacity="0.6"/>';
        $svg .= '<circle cx="' . ($width / 2) . '" cy="' . ($iconY - 10) . '" r="2" fill="white" opacity="0.4"/>';
        
        $svg .= '</svg>';
        
        return $svg;
    }

    private function wrapText($text, $maxLength)
    {
        if (strlen($text) <= $maxLength) {
            return $text;
        }
        
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';
        
        foreach ($words as $word) {
            if (strlen($currentLine . ' ' . $word) <= $maxLength) {
                $currentLine .= ($currentLine ? ' ' : '') . $word;
            } else {
                if ($currentLine) {
                    $lines[] = $currentLine;
                }
                $currentLine = $word;
            }
        }
        
        if ($currentLine) {
            $lines[] = $currentLine;
        }
        
        return implode("\n", array_slice($lines, 0, 2)); // Max 2 lines
    }
}
