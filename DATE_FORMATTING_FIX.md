# Date Formatting Fix - Library Management System

## Problem
The error "Call to a member function format() on null" was occurring in the profile edit page and other views when trying to format date fields that were null in the database.

## Root Cause
The issue was caused by calling the `format()` method on date fields that could be null:
- `$borrowing->borrowed_date->format('M d, Y')`
- `$borrowing->due_date->format('M d, Y')`
- `$borrowing->returned_date->format('M d, Y')`

When these date fields were null in the database, calling `format()` on them resulted in the error.

## Solution Implemented

### 1. Fixed Borrowing Model (`app/Models/Borrowing.php`)
- **Updated `isOverdue()` method:**
  ```php
  public function isOverdue()
  {
      return $this->status === 'borrowed' && $this->due_date && $this->due_date->isPast();
  }
  ```

- **Added safe date formatting accessors:**
  ```php
  public function getFormattedBorrowedDateAttribute()
  {
      return $this->borrowed_date ? $this->borrowed_date->format('M d, Y') : '-';
  }

  public function getFormattedDueDateAttribute()
  {
      return $this->due_date ? $this->due_date->format('M d, Y') : '-';
  }

  public function getFormattedReturnedDateAttribute()
  {
      return $this->returned_date ? $this->returned_date->format('M d, Y') : '-';
  }
  ```

### 2. Fixed Profile Page (`resources/views/users/profile.blade.php`)
- Added null checks before calling `format()`:
  ```blade
  @if($borrowing->borrowed_date)
      {{ $borrowing->borrowed_date->format('M d, Y') }}
  @else
      -
  @endif
  ```

### 3. Fixed Dashboard Page (`resources/views/dashboard.blade.php`)
- Added null checks for all date formatting calls
- Protected against null dates in overdue calculations

### 4. Fixed Borrowings Index Page (`resources/views/borrowings/index.blade.php`)
- Added null checks for all date fields
- Protected overdue status calculations

## Files Modified

1. **`app/Models/Borrowing.php`**
   - Fixed `isOverdue()` method
   - Added safe date formatting accessors

2. **`resources/views/users/profile.blade.php`**
   - Added null checks for `borrowed_date`, `due_date`, `returned_date`

3. **`resources/views/dashboard.blade.php`**
   - Added null checks for all date formatting

4. **`resources/views/borrowings/index.blade.php`**
   - Added null checks for all date formatting

5. **`app/Console/Commands/TestDateFormatting.php`** (New)
   - Created test command to verify date formatting functionality

## Testing

### Run Date Formatting Test
```bash
php artisan test:date-formatting
```

This command will:
- Check all borrowings for null date issues
- Test the new accessor methods
- Verify that date formatting works correctly

### Expected Output
```
Testing date formatting functionality...
Found 5 borrowings.
✓ Borrowing ID 1: Jul 16, 2025 | Jul 30, 2025 | Jul 16, 2025 | Overdue: No
✓ All borrowings have proper date formatting.
Date formatting test completed!
```

## Prevention

To prevent similar issues in the future:

1. **Always check for null before calling date methods:**
   ```php
   if ($date && $date->format('Y-m-d')) {
       // Safe to use
   }
   ```

2. **Use the new accessor methods:**
   ```php
   $borrowing->formatted_borrowed_date  // Returns formatted date or '-'
   $borrowing->formatted_due_date       // Returns formatted date or '-'
   $borrowing->formatted_returned_date  // Returns formatted date or '-'
   ```

3. **Add database constraints:**
   - Consider making required date fields `NOT NULL` in migrations
   - Add default values where appropriate

## Database Considerations

If you want to prevent null dates in the future, you can update the migrations:

```php
// In the borrowings table migration
$table->date('borrowed_date')->nullable(false);
$table->date('due_date')->nullable(false);
$table->date('returned_date')->nullable();
```

## Status
✅ **Fixed** - All date formatting issues have been resolved
✅ **Tested** - Date formatting functionality verified
✅ **Documented** - Prevention measures documented

The profile edit page and all other views should now work without the "Call to a member function format() on null" error. 