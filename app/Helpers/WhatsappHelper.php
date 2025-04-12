<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class WhatsappHelper
{
    public static function send($to, $message)
    {
        $token = env('FONNTE_TOKEN'); // ambil token dari .env

        return Http::withHeaders([
            'Authorization' => $token,
        ])->post('https://api.fonnte.com/send', [
            'target' => $to,
            'message' => $message,
            'countryCode' => '62', // default Indonesia
        ]);
    }
}
