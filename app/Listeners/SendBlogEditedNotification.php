<?php

namespace App\Listeners;

use App\Events\BlogEdited;
use App\Mail\BlogEditedMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendBlogEditedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BlogEdited $event): void
    {
        // Get all users
        $users = User::all();

        // Send email to each user
        foreach ($users as $user) {
            Mail::to($user->email)->send(new BlogEditedMail($event->blog));
        }
    }
}
