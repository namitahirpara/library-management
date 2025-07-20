# Book Image Upload Functionality Guide

## Overview
The Library Management System now supports uploading and displaying book cover images. This functionality allows users to upload real book cover images when creating or editing books.

## Features

### 1. Image Upload
- **Supported Formats**: JPEG, PNG, JPG, GIF, WebP
- **Maximum File Size**: 2MB
- **Minimum Dimensions**: 100x100 pixels
- **Recommended Size**: 300x400 pixels for optimal display

### 2. Image Preview
- Real-time preview when selecting an image file
- Client-side validation for file type and size
- Option to remove selected image before upload

### 3. Image Storage
- Images are stored in `storage/app/public/book-covers/`
- Unique filenames are generated to prevent conflicts
- Old images are automatically deleted when replaced

### 4. Image Display
- Images are displayed on the books index page
- Fallback to styled placeholder if no image is uploaded
- Responsive design with hover effects

## How to Use

### Adding a Book with Cover Image

1. **Navigate to Add Book Page**
   - Go to Books → Add Book (Admin/Librarian only)

2. **Fill in Book Details**
   - Enter all required book information

3. **Upload Cover Image**
   - Click "Choose File" in the Cover Image section
   - Select an image file (JPEG, PNG, JPG, GIF, WebP)
   - Preview will appear automatically
   - Click "Remove Image" if you want to change the selection

4. **Save the Book**
   - Click "Add Book" to save with the uploaded image

### Editing a Book's Cover Image

1. **Navigate to Edit Book Page**
   - Go to Books → Select a book → Edit

2. **View Current Image**
   - Current cover image is displayed (if exists)

3. **Upload New Image**
   - Click "Choose File" to select a new image
   - Preview will show the new image
   - Old image will be replaced when saved

4. **Save Changes**
   - Click "Update Book" to save the new image

## Technical Details

### File Storage
- **Location**: `storage/app/public/book-covers/`
- **Naming**: `{timestamp}_{unique_id}.{extension}`
- **Access**: Via `/storage/book-covers/` URL

### Database
- **Field**: `cover_image` in `books` table
- **Type**: VARCHAR(255)
- **Content**: Relative path to stored image

### Validation Rules
```php
'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048|dimensions:min_width=100,min_height=100'
```

### Model Accessor
```php
public function getCoverImageUrlAttribute()
{
    if (!$this->cover_image) {
        return null;
    }
    
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
```

## Troubleshooting

### Image Not Displaying
1. **Check Storage Link**
   ```bash
   php artisan storage:link
   ```

2. **Check File Permissions**
   ```bash
   chmod -R 755 storage/
   ```

3. **Verify File Exists**
   ```bash
   php artisan test:image-upload
   ```

### Upload Errors
1. **File Too Large**: Ensure image is under 2MB
2. **Invalid Format**: Use only JPEG, PNG, JPG, GIF, or WebP
3. **Image Too Small**: Ensure image is at least 100x100 pixels

### Common Issues
- **Storage Link Missing**: Run `php artisan storage:link`
- **Directory Permissions**: Ensure web server can write to storage directory
- **File Not Found**: Check if image file exists in storage location
- **Placeholder Images Showing**: Clear generated placeholders with `php artisan books:clear-placeholders`
- **Real Images Not Displaying**: Check if images are actual uploaded files (not SVG placeholders)

## Testing

### Run Image Upload Test
```bash
php artisan test:image-upload
```

### Generate Placeholder Images
```bash
php artisan books:generate-covers
```

### Clear Placeholder Images
```bash
php artisan books:clear-placeholders
```

## Security Considerations

1. **File Type Validation**: Only image files are accepted
2. **File Size Limits**: Maximum 2MB to prevent abuse
3. **Unique Filenames**: Prevents filename conflicts
4. **Automatic Cleanup**: Old images are deleted when replaced

## Performance Optimization

1. **Image Compression**: Consider compressing images before upload
2. **CDN Integration**: For production, consider using a CDN for image delivery
3. **Thumbnail Generation**: For large images, consider generating thumbnails

## Future Enhancements

1. **Image Cropping**: Add ability to crop images to specific dimensions
2. **Multiple Image Support**: Support for multiple book cover angles
3. **Image Optimization**: Automatic image compression and optimization
4. **External Image URLs**: Support for linking to external image URLs 