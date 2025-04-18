<?php

// app/Helpers/WhatsappHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class WhatsappHelper
{
    public static function send($to, $message)
    {
        $token = env('FONNTE_TOKEN');

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->post('https://api.fonnte.com/send', [
            'target' => $to,
            'message' => $message,
            'countryCode' => '62',
        ]);

        return [
            'number' => $to,
            'status' => $response->successful() ? 'success' : 'failed',
            'fonnte_response' => $response->json()
        ];
    }
}
