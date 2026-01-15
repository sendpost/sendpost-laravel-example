<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
    /**
     * Send an email using SendPost API directly.
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string',
            'htmlBody' => 'required|string',
            'textBody' => 'nullable|string',
        ]);

        try {
            $emailMessage = [
                'from' => [
                    'email' => config('sendpost.from_email'),
                    'name' => config('sendpost.from_name'),
                ],
                'to' => [
                    ['email' => $validated['to']],
                ],
                'subject' => $validated['subject'],
                'htmlBody' => $validated['htmlBody'],
                'textBody' => $validated['textBody'] ?? strip_tags($validated['htmlBody']),
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
                    'X-SubAccount-ApiKey: ' . config('sendpost.api_key'),
                ],
                CURLOPT_RETURNTRANSFER => true,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode >= 400) {
                return response()->json([
                    'success' => false,
                    'error' => "API error: {$response}",
                ], 500);
            }

            $result = json_decode($response, true);

            return response()->json([
                'success' => true,
                'messageId' => $result[0]['messageId'] ?? $result['messageId'] ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
