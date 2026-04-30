<?php

namespace App\Services;

use App\Contracts\EmailNotificationInterface;
use Exception;

class MailgunService implements EmailNotificationInterface
{
    protected $apiKey;
    protected $domain;
    protected $apiEndpoint;

    public function __construct()
    {
        $this->apiKey = env('MAILGUN_SECRET', '');
        $this->domain = env('MAILGUN_DOMAIN', '');
        $this->apiEndpoint = "https://api.mailgun.net/v3/{$this->domain}/messages";
    }

    /**
     * Send an email notification via Mailgun.
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
            $postData = [
                'from' => config('mail.from.address'),
                'to' => $to,
                'subject' => $subject,
                'text' => $message
            ];

            $response = $this->makeRequest($postData, $attachments);
            return $response;
        } catch (Exception $e) {
            report($e);
            return [
                'success' => false,
                'message' => 'Mailgun Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send an email notification to multiple recipients via Mailgun.
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
            $postData = [
                'from' => config('mail.from.address'),
                'to' => implode(',', $recipients),
                'subject' => $subject,
                'text' => $message
            ];

            $response = $this->makeRequest($postData, $attachments);
            if ($response['success']) {
                return [
                    'success' => true,
                    'message' => 'Bulk emails sent successfully via Mailgun to ' . count($recipients) . ' recipients!'
                ];
            }

            return $response;
        } catch (Exception $e) {
            report($e);
            return [
                'success' => false,
                'message' => 'Mailgun Bulk Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send an email with HTML content via Mailgun.
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
            $postData = [
                'from' => config('mail.from.address'),
                'to' => $to,
                'subject' => $subject,
                'html' => $htmlContent
            ];

            $response = $this->makeRequest($postData, $attachments);
            return $response;
        } catch (Exception $e) {
            report($e);
            return [
                'success' => false,
                'message' => 'Mailgun HTML Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Make HTTP request to Mailgun API.
     *
     * @param array $postData
     * @param array $attachments
     * @return array
     */
    protected function makeRequest(array $postData, array $attachments = []): array
    {
        try {
            $response = curl_init($this->apiEndpoint);
            curl_setopt($response, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($response, CURLOPT_TIMEOUT, 10);
            curl_setopt($response, CURLOPT_POST, true);
            curl_setopt($response, CURLOPT_USERPWD, 'api:' . $this->apiKey);

            // Handle attachments - support multiple files
            $curlFile = [];
            $attachmentIndex = 0;
            foreach ($attachments as $attachment) {
                if (file_exists($attachment)) {
                    $curlFile['attachment[' . $attachmentIndex . ']'] = curl_file_create($attachment);
                    $attachmentIndex++;
                }
            }

            $postData = array_merge($postData, $curlFile);
            curl_setopt($response, CURLOPT_POSTFIELDS, $postData);

            $result = curl_exec($response);
            $statusCode = curl_getinfo($response, CURLINFO_HTTP_CODE);
            $error = curl_error($response);
            curl_close($response);

            if ($error) {
                return [
                    'success' => false,
                    'message' => 'Mailgun Request Error: ' . $error
                ];
            }

            if ($statusCode >= 200 && $statusCode < 300) {
                return [
                    'success' => true,
                    'message' => 'Email sent successfully'
                ];
            }

            return [
                'success' => false,
                'message' => 'Mailgun API Error: HTTP ' . $statusCode . ' - ' . $result
            ];
        } catch (Exception $e) {
            report($e);
            return [
                'success' => false,
                'message' => 'Mailgun Request Error: ' . $e->getMessage()
            ];
        }
    }
}
