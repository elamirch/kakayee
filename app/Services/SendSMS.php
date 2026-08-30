<?php

namespace App\Services;
use App\Services\Curl;

class SendSMS {

    private $SMS_API_URL;
    private $curl;

    public function __construct()
    {
        $this->SMS_API_URL = env('SMS_API_URL');
        $this->curl = new Curl;
    }
    
    public function otp($phoneNumber, $otp_code) {

        $payload = http_build_query([
            'receptor' => $phoneNumber,
            'token' => $otp_code,
            'template' => 'otp-kcp'
        ]);

        return json_decode($this->curl->curl($this->SMS_API_URL, $payload));
    }
}