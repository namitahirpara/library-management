<?php

namespace App\Console\Commands;

use App\Models\Borrowing;
use Illuminate\Console\Command;

class TestDateFormatting extends Command
{
    protected $signature = 'test:date-formatting';
    protected $description = 'Test date formatting functionality and identify null date issues';

    public function handle()
    {
        $this->info('Testing date formatting functionality...');
        
        $borrowings = Borrowing::all();
        
        if ($borrowings->isEmpty()) {
            $this->info('No borrowings found.');
            return;
        }
        
        $this->info("Found {$borrowings->count()} borrowings.");
        
        $nullDates = 0;
        foreach ($borrowings as $borrowing) {
            $issues = [];
            
            if (!$borrowing->borrowed_date) {
                $issues[] = 'borrowed_date is null';
            }
            
            if (!$borrowing->due_date) {
                $issues[] = 'due_date is null';
            }
            
            if (!$borrowing->returned_date && $borrowing->status === 'returned') {
                $issues[] = 'returned_date is null but status is returned';
            }
            
            if (!empty($issues)) {
                $this->warn("Borrowing ID {$borrowing->id} ({$borrowing->book->title}): " . implode(', ', $issues));
                $nullDates++;
            }
            
            // Test the new accessor methods
            try {
                $formattedBorrowed = $borrowing->formatted_borrowed_date;
                $formattedDue = $borrowing->formatted_due_date;
                $formattedReturned = $borrowing->formatted_returned_date;
                $isOverdue = $borrowing->isOverdue();
                
                $this->line("✓ Borrowing ID {$borrowing->id}: {$formattedBorrowed} | {$formattedDue} | {$formattedReturned} | Overdue: " . ($isOverdue ? 'Yes' : 'No'));
            } catch (\Exception $e) {
                $this->error("✗ Error with Borrowing ID {$borrowing->id}: " . $e->getMessage());
            }
        }
        
        if ($nullDates > 0) {
            $this->warn("Found {$nullDates} borrowings with null date issues.");
        } else {
            $this->info("✓ All borrowings have proper date formatting.");
        }
        
        $this->info('Date formatting test completed!');
    }
}
