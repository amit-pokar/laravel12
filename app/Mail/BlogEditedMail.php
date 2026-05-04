<?php

namespace App\Mail;

use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BlogEditedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $blog;

    /**
     * Create a new message instance.
     */
    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Blog Updated: ' . $this->blog->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.blog-edited',
            with: [
                'blog' => $this->blog,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
