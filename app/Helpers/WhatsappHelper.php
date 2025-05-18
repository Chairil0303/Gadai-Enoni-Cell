<?php

namespace App\Helpers;

use App\Models\WhatsappTemplate;
use Illuminate\Support\Facades\Http;

class WhatsappHelper
{
    /**
     * Kirim pesan WhatsApp menggunakan template dari database
     *
     * @param string $to Nomor tujuan (format 62xxx)
     * @param string $type Jenis template (perpanjang/tebus)
     * @param array $data Data untuk menggantikan placeholder
     * @return array Response dari Fonnte API
     */
    public static function send($to, $type, $data)
    {
        // Ambil template dari database
        $template = WhatsappTemplate::where('type', $type)
        ->where('is_active', true)
        ->first();

        if (!$template) {
            \Log::error("Template WhatsApp untuk tipe '$type' tidak ditemukan.");
            return [
                'number' => $to,
                'status' => 'failed',
                'message' => 'Template tidak ditemukan'
            ];
        }

        // Render template dengan data
        $message = self::renderMessage($template->message, $data);

        // Ambil token Fonnte
        $token = env('FONNTE_TOKEN');

        // Kirim request ke Fonnte
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

    /**
     * Render template WhatsApp dengan data dinamis
     *
     * @param string $template Template pesan dengan placeholder
     * @param array $data Data untuk menggantikan placeholder
     * @return string Pesan yang sudah dirender
     */
    private static function renderMessage($template, $data)
    {
        foreach ($data as $key => $value) {
            $template = str_replace("{{{$key}}}", $value, $template);
        }
        return $template;
    }
}
