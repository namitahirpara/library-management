<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'category',
        'quantity',
        'available_quantity',
        'publisher',
        'publication_year',
        'cover_image'
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'quantity' => 'integer',
        'available_quantity' => 'integer',
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function isAvailable()
    {
        return $this->available_quantity > 0;
    }

    public function canBeBorrowed()
    {
        return $this->isAvailable();
    }

    public function getCoverImageUrlAttribute()
    {
        if (!$this->cover_image) {
            return null;
        }
        
        // Check if the file exists in storage
        if (!Storage::disk('public')->exists($this->cover_image)) {
            return null;
        }
        
        return asset('storage/' . $this->cover_image);
    }

    public function getCoverImageThumbnailUrlAttribute()
    {
        if (!$this->cover_image) {
            return null;
        }
        
        // Check if the file exists in storage
        if (!Storage::disk('public')->exists($this->cover_image)) {
            return null;
        }
        
        return Storage::disk('public')->url($this->cover_image);
    }

    public function hasRealCoverImage()
    {
        if (!$this->cover_image) {
            return false;
        }
        
        // Check if it's a generated SVG placeholder
        if (str_contains($this->cover_image, '.svg')) {
            return false;
        }
        
        // Check if the file exists in storage
        return Storage::disk('public')->exists($this->cover_image);
    }
} 