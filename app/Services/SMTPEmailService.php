<?php

namespace App\Services;

use App\Contracts\EmailNotificationInterface;
use Illuminate\Support\Facades\Mail;
use Exception;

class SMTPEmailService implements EmailNotificationInterface
{
    /**
     * Send an email notification via SMTP.
     *
     * @param string $to
     * @param string $subject
     * @param string $message
     * @param array $attachments
     * @return array
     */
    public function send(string $to, string $subject, string $message, array $attachments = []): array
    {
        try {
            Mail::raw($message, function ($mail) use ($to, $subject, $attachments) {
                $mail->to($to)
                    ->subject($subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));

                foreach ($attachments as $attachment) {
                    $mail->attach($attachment);
                }
            });

            return [
                'success' => true,
                'message' => 'Email sent successfully via SMTP!'
            ];
        } catch (Exception $e) {
            report($e);
            return [
                'success' => false,
                'message' => 'SMTP Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send an email notification to multiple recipients via SMTP.
     *
     * @param array $recipients
     * @param string $subject
     * @param string $message
     * @param array $attachments
     * @return array
     */
    public function sendBulk(array $recipients, string $subject, string $message, array $attachments = []): array
    {
        try {
            $successCount = 0;
            $failedCount = 0;

            foreach ($recipients as $recipient) {
                $result = $this->send($recipient, $subject, $message, $attachments);
                if ($result['success']) {
                    $successCount++;
                } else {
                    $failedCount++;
                }
            }

            return [
                'success' => $failedCount === 0,
                'message' => "Emails sent successfully! Successful: {$successCount}, Failed: {$failedCount}"
            ];
        } catch (Exception $e) {
            report($e);
            return [
                'success' => false,
                'message' => 'SMTP Bulk Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send an email with HTML content via SMTP.
     *
     * @param string $to
     * @param string $subject
     * @param string $htmlContent
     * @param array $attachments
     * @return array
     */
    public function sendHtml(string $to, string $subject, string $htmlContent, array $attachments = []): array
    {
        try {
            Mail::html($htmlContent, function ($mail) use ($to, $subject, $attachments) {
                $mail->to($to)
                    ->subject($subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));

                foreach ($attachments as $attachment) {
                    $mail->attach($attachment);
                }
            });

            return [
                'success' => true,
                'message' => 'HTML Email sent successfully via SMTP!'
            ];
        } catch (Exception $e) {
            report($e);
            return [
                'success' => false,
                'message' => 'SMTP HTML Error: ' . $e->getMessage()
            ];
        }
    }
}
