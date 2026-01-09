<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send WhatsApp message using Fonnte API (Example)
     * You can replace this with any other provider (Twilio, Wablas, etc)
     */
    public static function send($phone, $message)
    {
        $token = env('WA_API_TOKEN'); // Configure this in .env

        if (!$token) {
            Log::warning('WhatsApp API Token not configured.');
            return false;
        }

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62', // Default to Indonesia
            ]);

            if ($response->successful()) {
                Log::info("WA sent to $phone");
                return true;
            } else {
                Log::error("WA failed: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("WA Exception: " . $e->getMessage());
            return false;
        }
    }
}
