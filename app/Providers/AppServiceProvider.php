<?php

namespace App\Providers;

use App\Events\BlogCreated;
use App\Events\BlogDeleted;
use App\Events\BlogEdited;
use App\Listeners\SendBlogCreatedNotification;
use App\Listeners\SendBlogDeletedNotification;
use App\Listeners\SendBlogEditedNotification;
use App\Models\Blog;
use App\Observers\BlogObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Blog observer for slug auto-generation
        Blog::observe(BlogObserver::class);

        Event::listen(BlogCreated::class, [SendBlogCreatedNotification::class, 'handle']);
        Event::listen(BlogEdited::class, [SendBlogEditedNotification::class, 'handle']);
        Event::listen(BlogDeleted::class, [SendBlogDeletedNotification::class, 'handle']);
    }
}
