<?php

namespace App\Jobs;

use App\Models\Blog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class GenerateBlogThumbnail implements ShouldQueue
{
    use Queueable;

    protected $blog;

    /**
     * Create a new job instance.
     */
    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Check if blog has an image
        if (!$this->blog->image || !Storage::disk('public')->exists($this->blog->image)) {
            return;
        }

        try {
            $imagePath = Storage::disk('public')->path($this->blog->image);
            
            // Get image dimensions
            $imageInfo = getimagesize($imagePath);
            if (!$imageInfo) {
                return;
            }

            // Read the original image
            $sourceImage = imagecreatefromstring(file_get_contents($imagePath));
            if (!$sourceImage) {
                return;
            }

            // Thumbnail dimensions
            $thumbWidth = 200;
            $thumbHeight = 150;

            // Create thumbnail
            $thumbnail = imagecreatetruecolor($thumbWidth, $thumbHeight);
            
            // Get original dimensions
            $origWidth = $imageInfo[0];
            $origHeight = $imageInfo[1];

            // Resize and crop to maintain aspect ratio
            imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $origWidth, $origHeight);

            // Save thumbnail
            $thumbFileName = 'thumbnails/' . basename($this->blog->image);
            $thumbPath = Storage::disk('public')->path($thumbFileName);

            // Create thumbnails directory if it doesn't exist
            @mkdir(dirname($thumbPath), 0755, true);

            // Save based on image type
            $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
            if ($extension === 'png') {
                imagepng($thumbnail, $thumbPath);
            } elseif ($extension === 'gif') {
                imagegif($thumbnail, $thumbPath);
            } else {
                imagejpeg($thumbnail, $thumbPath, 85);
            }

            // Update blog thumbnail path
            $this->blog->update(['thumbnail' => $thumbFileName]);

            // Free up memory
            imagedestroy($sourceImage);
            imagedestroy($thumbnail);

        } catch (\Exception $e) {
            \Log::error('Thumbnail generation failed for blog ' . $this->blog->id . ': ' . $e->getMessage());
        }
    }
}
