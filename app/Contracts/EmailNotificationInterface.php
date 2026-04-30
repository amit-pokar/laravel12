<?php

namespace App\Contracts;

interface EmailNotificationInterface
{
    /**
     * Send an email notification.
     *
     * @param string $to
     * @param string $subject
     * @param string $message
     * @param array $attachments
     * @return array ['success' => bool, 'message' => string]
     */
    public function send(string $to, string $subject, string $message, array $attachments = []): array;

    /**
     * Send an email notification to multiple recipients.
     *
     * @param array $recipients
     * @param string $subject
     * @param string $message
     * @param array $attachments
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendBulk(array $recipients, string $subject, string $message, array $attachments = []): array;

    /**
     * Send an email with HTML content.
     *
     * @param string $to
     * @param string $subject
     * @param string $htmlContent
     * @param array $attachments
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendHtml(string $to, string $subject, string $htmlContent, array $attachments = []): array;
}
