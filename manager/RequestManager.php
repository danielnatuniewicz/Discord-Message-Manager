<?php
class RequestManager{
    private $tokenManager;

    public function __construct(TokenManager $tokenManager){
        $this->tokenManager = $tokenManager;
    }

    public function sendRequest($url, $method = "GET"){
        $headers = [
            'Authorization: ' . $this->tokenManager->getToken(),
            'Content-Type: application/json',
        ];

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ];

        if ($method === 'DELETE') {
            $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        }
        
        $ch = curl_init();
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        
        curl_close($ch);
        
        return $response;
    }

    public function getUser(){
        $url = "https://discord.com/api/v9/users/@me";

        return $this->sendRequest($url);
    }
}