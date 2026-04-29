<?php

namespace App\Services;

use App\Contracts\EmailNotificationInterface;
use Exception;

class SendGridService implements EmailNotificationInterface
{
    protected $apiKey;
    protected $apiEndpoint = 'https://api.sendgrid.com/v3/mail/send';

    public function __construct()
    {
        $this->apiKey = env('SENDGRID_API_KEY', '');
    }

    /**
     * Send an email notification via SendGrid.
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
            $payload = [
                'personalizations' => [
                    [
                        'to' => [
                            ['email' => $to]
                        ],
                        'subject' => $subject
                    ]
                ],
                'from' => [
                    'email' => config('mail.from.address'),
                    'name' => config('mail.from.name')
                ],
                'content' => [
                    [
                        'type' => 'text/plain',
                        'value' => $message
                    ]
                ]
            ];

            // Add attachments if provided
            if (!empty($attachments)) {
                $payload['attachments'] = $this->prepareAttachments($attachments);
            }

            $response = $this->makeRequest($payload);
            if ($response['success']) {
                return [
                    'success' => true,
                    'message' => 'Email sent successfully via SendGrid!'
                ];
            }

            return [
                'success' => false,
                'message' => 'SendGrid Error: ' . ($response['message'] ?? 'Unknown error')
            ];
        } catch (Exception $e) {
            report($e);
            return [
                'success' => false,
                'message' => 'SendGrid Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send an email notification to multiple recipients via SendGrid.
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
            $personalizations = [];
            foreach ($recipients as $recipient) {
                $personalizations[] = [
                    'to' => [
                        ['email' => $recipient]
                    ],
                    'subject' => $subject
                ];
            }

            $payload = [
                'personalizations' => $personalizations,
                'from' => [
                    'email' => config('mail.from.address'),
                    'name' => config('mail.from.name')
                ],
                'content' => [
                    [
                        'type' => 'text/plain',
                        'value' => $message
                    ]
                ]
            ];

            // Add attachments if provided
            if (!empty($attachments)) {
                $payload['attachments'] = $this->prepareAttachments($attachments);
            }

            $response = $this->makeRequest($payload);
            if ($response['success']) {
                return [
                    'success' => true,
                    'message' => 'Bulk emails sent successfully via SendGrid to ' . count($recipients) . ' recipients!'
                ];
            }

            return [
                'success' => false,
                'message' => 'SendGrid Bulk Error: ' . ($response['message'] ?? 'Unknown error')
            ];
        } catch (Exception $e) {
            report($e);
            return [
                'success' => false,
                'message' => 'SendGrid Bulk Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send an email with HTML content via SendGrid.
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
            $payload = [
                'personalizations' => [
                    [
                        'to' => [
                            ['email' => $to]
                        ],
                        'subject' => $subject
                    ]
                ],
                'from' => [
                    'email' => config('mail.from.address'),
                    'name' => config('mail.from.name')
                ],
                'content' => [
                    [
                        'type' => 'text/html',
                        'value' => $htmlContent
                    ]
                ]
            ];

            // Add attachments if provided
            if (!empty($attachments)) {
                $payload['attachments'] = $this->prepareAttachments($attachments);
            }

            $response = $this->makeRequest($payload);
            if ($response['success']) {
                return [
                    'success' => true,
                    'message' => 'HTML Email sent successfully via SendGrid!'
                ];
            }

            return [
                'success' => false,
                'message' => 'SendGrid HTML Error: ' . ($response['message'] ?? 'Unknown error')
            ];
        } catch (Exception $e) {
            report($e);
            return [
                'success' => false,
                'message' => 'SendGrid HTML Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Prepare attachments for SendGrid API.
     *
     * @param array $attachments
     * @return array
     */
    protected function prepareAttachments(array $attachments): array
    {
        $attachmentData = [];

        foreach ($attachments as $filePath) {
            if (file_exists($filePath)) {
                $attachmentData[] = [
                    'content' => base64_encode(file_get_contents($filePath)),
                    'filename' => basename($filePath),
                    'type' => mime_content_type($filePath) ?: 'application/octet-stream'
                ];
            }
        }

        return $attachmentData;
    }

    /**
     * Make HTTP request to SendGrid API.
     *
     * @param array $payload
     * @return array
     */
    protected function makeRequest(array $payload): array
    {
        try {
            $response = curl_init($this->apiEndpoint);
            curl_setopt($response, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($response, CURLOPT_TIMEOUT, 10);
            curl_setopt($response, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($response, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($response, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json'
            ]);

            $result = curl_exec($response);
            $statusCode = curl_getinfo($response, CURLINFO_HTTP_CODE);
            $error = curl_error($response);
            curl_close($response);

            if ($error) {
                return [
                    'success' => false,
                    'message' => 'SendGrid Request Error: ' . $error
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
                'message' => 'SendGrid API Error: HTTP ' . $statusCode . ' - ' . $result
            ];
        } catch (Exception $e) {
            report($e);
            return [
                'success' => false,
                'message' => 'SendGrid Request Error: ' . $e->getMessage()
            ];
        }
    }
}
