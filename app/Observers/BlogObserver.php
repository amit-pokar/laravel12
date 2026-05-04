<?php

namespace App\Observers;

use App\Jobs\GenerateBlogThumbnail;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogObserver
{
    /**
     * Handle the Blog "creating" event.
     */
    public function creating(Blog $blog): void
    {
        // Auto-generate slug from name if slug is empty
        if (empty($blog->slug)) {
            $blog->slug = Str::slug($blog->name);
        }
    }

    /**
     * Handle the Blog "updating" event.
     */
    public function updating(Blog $blog): void
    {
        // Auto-generate slug from name if it has changed
        if ($blog->isDirty('name') || empty($blog->slug)) {
            $blog->slug = Str::slug($blog->name);
        }
    }

    /**
     * Handle the Blog "created" event.
     */
    public function created(Blog $blog): void
    {
        // Dispatch job to generate thumbnail if image exists
        if ($blog->image) {
            GenerateBlogThumbnail::dispatch($blog);
        }
    }

    /**
     * Handle the Blog "updated" event.
     */
    public function updated(Blog $blog): void
    {
        // Dispatch job to generate thumbnail if image was changed
        if ($blog->isDirty('image') && $blog->image) {
            // Delete old thumbnail if it exists
            if ($blog->getOriginal('thumbnail') && Storage::disk('public')->exists($blog->getOriginal('thumbnail'))) {
                Storage::disk('public')->delete($blog->getOriginal('thumbnail'));
            }
            GenerateBlogThumbnail::dispatch($blog);
        }
    }

    /**
     * Handle the Blog "deleted" event.
     */
    public function deleted(Blog $blog): void
    {
        // Delete image and thumbnail when blog is deleted
        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }
        if ($blog->thumbnail && Storage::disk('public')->exists($blog->thumbnail)) {
            Storage::disk('public')->delete($blog->thumbnail);
        }
    }

    /**
     * Handle the Blog "restored" event.
     */
    public function restored(Blog $blog): void
    {
        //
    }

    /**
     * Handle the Blog "force deleted" event.
     */
    public function forceDeleted(Blog $blog): void
    {
        // Delete image and thumbnail when blog is force deleted
        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }
        if ($blog->thumbnail && Storage::disk('public')->exists($blog->thumbnail)) {
            Storage::disk('public')->delete($blog->thumbnail);
        }
    }
}
