<?php

namespace App\Mail\Drivers;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Component\Mime\Email;

class SendPostTransport extends AbstractTransport
{
    protected string $apiKey;
    protected string $fromEmail;
    protected string $fromName;

    public function __construct(string $apiKey, string $fromEmail, string $fromName)
    {
        parent::__construct();
        $this->apiKey = $apiKey;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        
        $this->sendViaSendPost($email);
    }

    protected function sendViaSendPost(Email $email): void
    {
        $from = $email->getFrom()[0] ?? null;
        $toAddresses = $email->getTo();
        
        $recipients = [];
        foreach ($toAddresses as $address) {
            $recipients[] = [
                'email' => $address->getAddress(),
                'name' => $address->getName(),
            ];
        }

        $emailMessage = [
            'from' => [
                'email' => $from ? $from->getAddress() : $this->fromEmail,
                'name' => $from ? $from->getName() : $this->fromName,
            ],
            'to' => $recipients,
            'subject' => $email->getSubject(),
            'htmlBody' => $email->getHtmlBody(),
            'textBody' => $email->getTextBody(),
            'trackOpens' => true,
            'trackClicks' => true,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.sendpost.io/api/v1/subaccount/email/',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($emailMessage),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'X-SubAccount-ApiKey: ' . $this->apiKey,
            ],
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400) {
            throw new \RuntimeException("SendPost API error: {$response}");
        }
    }

    public function __toString(): string
    {
        return 'sendpost';
    }
}
