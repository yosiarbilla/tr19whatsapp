<?php

namespace App\Services;

use Twilio\Rest\Client as TwilioClient;

class TwilioService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new TwilioClient(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    }

    public function sendWhatsAppMessage($to, $message, $mediaUrl = null)
{
    $data = [
        'from' => env('TWILIO_PHONE_NUMBER'),
        'body' => $message
    ];

    // ✅ Pastikan hanya menggunakan direct link ke gambar
    if ($mediaUrl && filter_var($mediaUrl, FILTER_VALIDATE_URL)) {
        $data['mediaUrl'] = [$mediaUrl];
    }

    $this->twilio->messages->create(
        "whatsapp:" . $this->formatPhoneNumber($to),
        $data
    );
}

    private function formatPhoneNumber($phone)
    {
        if (substr($phone, 0, 1) === "0") {
            return "+62" . substr($phone, 1);
        } elseif (!str_starts_with($phone, "+")) {
            return "+62" . $phone;
        }
        return $phone;
    }
}
