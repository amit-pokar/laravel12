<?php

namespace App\Http\Controllers;

use App\Contracts\EmailNotificationInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    protected $emailService;

    public function __construct(EmailNotificationInterface $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Show send email form
     */
    public function create()
    {
        return view('email.send');
    }

    /**
     * Process uploaded attachments and return their paths
     */
    protected function processAttachments(Request $request)
    {
        $attachments = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                // Store file in temporary directory
                $path = $file->store('email-attachments', 'local');
                // Use Storage::disk to get the proper full path with correct separators
                $fullPath = Storage::disk('local')->path($path);
                $attachments[] = $fullPath;
            }
        }

        return $attachments;
    }

    /**
     * Send a simple text email
     */
    public function send(Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240' // Max 10MB per file
        ]);

        $attachments = $this->processAttachments($request);

        $result = $this->emailService->send(
            $request->to,
            $request->subject,
            $request->message,
            $attachments
        );

        // Clean up temporary files
        $this->cleanupAttachments($attachments);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Send HTML email
     */
    public function sendHtml(Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'html_content' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240' // Max 10MB per file
        ]);

        $attachments = $this->processAttachments($request);

        $result = $this->emailService->sendHtml(
            $request->to,
            $request->subject,
            $request->html_content,
            $attachments
        );

        // Clean up temporary files
        $this->cleanupAttachments($attachments);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Send bulk emails
     */
    public function sendBulk(Request $request)
    {
        $request->validate([
            'recipients' => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240' // Max 10MB per file
        ]);

        // Parse comma-separated emails
        $recipients = array_map('trim', explode(',', $request->recipients));
        
        $attachments = $this->processAttachments($request);

        $result = $this->emailService->sendBulk(
            $recipients,
            $request->subject,
            $request->message,
            $attachments
        );

        // Clean up temporary files
        $this->cleanupAttachments($attachments);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Clean up temporary attachment files
     */
    protected function cleanupAttachments(array $attachments)
    {
        foreach ($attachments as $filePath) {
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
    }

    /**
     * Test email endpoint - simple GET request
     */
    public function test()
    {
        $testEmail = 'pokaramit2005@gmail.com';
        $testSubject = 'Test Email from ' . config('app.name');
        $testMessage = 'This is a test email sent via ' . env('EMAIL_PROVIDER', 'SMTP') . ' provider.';

        $result = $this->emailService->send(
            $testEmail,
            $testSubject,
            $testMessage
        );

        return response()->json([
            'status' => $result['success'] ? 'success' : 'failed',
            'message' => $result['message'],
            'provider' => env('EMAIL_PROVIDER', 'SMTP'),
            'recipient' => $testEmail
        ]);
    }
}
