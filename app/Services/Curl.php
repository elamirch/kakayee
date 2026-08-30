<?php

namespace App\Services;

class Curl
{
    public function curl(string $url, string $data = null, array $header = null, string $request = null) {
        //Session initialization
        $curl_session = curl_init($url);
        curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
        
        //Adding header if existed
        if(!is_null($header)) {
            curl_setopt($curl_session, CURLOPT_HTTPHEADER, $header);
        }

        //Adding data as postfields if existed
        if(!is_null($data)) {
            curl_setopt($curl_session, CURLOPT_POST, true);
            curl_setopt($curl_session, CURLOPT_POSTFIELDS, $data);
        }

        //Adding timeout
        curl_setopt($curl_session, CURLOPT_CONNECTTIMEOUT, 10);

        //Setting custom request if existed
        if($request == "DELETE") {
            curl_setopt($curl_session, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

        $response = curl_exec($curl_session);

        return $response;
    }
}