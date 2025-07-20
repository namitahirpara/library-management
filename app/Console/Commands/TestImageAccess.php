<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestImageAccess extends Command
{
    protected $signature = 'books:test-access';
    protected $description = 'Test if book cover images are accessible via HTTP';

    public function handle()
    {
        $this->info('Testing image accessibility...');
        
        $books = Book::whereNotNull('cover_image')->get();
        
        foreach ($books as $book) {
            if ($book->hasRealCoverImage()) {
                $this->line("Testing: {$book->title}");
                $this->line("  URL: {$book->cover_image_url}");
                
                try {
                    $response = Http::timeout(5)->get($book->cover_image_url);
                    $status = $response->status();
                    
                    if ($status === 200) {
                        $this->info("  ✓ Accessible (Status: {$status})");
                    } else {
                        $this->error("  ✗ Not accessible (Status: {$status})");
                    }
                } catch (\Exception $e) {
                    $this->error("  ✗ Error accessing image: " . $e->getMessage());
                }
                
                $this->newLine();
            }
        }
    }
} 