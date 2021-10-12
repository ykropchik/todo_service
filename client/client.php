#!/usr/bin/env
<?php

class ResponseResult {
    private $statusCode;
    private $data;

    public function __construct($statusCode, $data) {
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    public function getStatusCode() {
        return $this->statusCode;
    } 
    
    public function getData() {
        return $this->data;
    } 
}

class TodoService {
    private string $apiURL;
    private string $jwtToken;

    public function __construct(string $apiURL, string $jwtToken = null) {
        $this->apiURL = $apiURL;
        $this->jwtToken = $jwtToken;
    }

    public function getJWT() {
        return $this->jwtToken;
    }

    public function registration(string $username, string $password) {
        $data = json_encode(['username' => $username, 'password' => $password], JSON_UNESCAPED_UNICODE);
        $request = curl_init();
        curl_setopt_array(
            $request, 
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $this->apiURL.'/registration',
                CURLOPT_HTTPHEADER => array('Content-type: application/json', 'Content-Length: '.strlen($data)),
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data
            ));
    
        try {
            $data = curl_exec($request);
            $http_code = curl_getinfo($request, CURLINFO_RESPONSE_CODE);
        } finally {
            curl_close($request);
        }

        return new ResponseResult($http_code, $data);
    }

    public function auth(string $username, string $password) {
        $data = json_encode(['username' => $username, 'password' => $password], JSON_UNESCAPED_UNICODE);
        $request = curl_init();
        curl_setopt_array(
            $request, 
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $this->apiURL.'/api/login_check',
                CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'Content-Length: '.strlen($data)),
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data
            ));
         
        try {
            $data = curl_exec($request);
            $http_code = curl_getinfo($request, CURLINFO_RESPONSE_CODE);
        } finally {
            curl_close($request);
        }

        return new ResponseResult($http_code, $data);
    }

    public function getTodoList() {
        $request = curl_init();
        curl_setopt_array(
            $request, 
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $this->apiURL.'/todo/',
                CURLOPT_HTTPHEADER => array('JWT-Token: '.$this->jwtToken),
                CURLOPT_CUSTOMREQUEST => 'GET'
            ));

        try {
            $data = curl_exec($request);
            $http_code = curl_getinfo($request, CURLINFO_RESPONSE_CODE);
        } finally {
            curl_close($request);
        }

        return new ResponseResult($http_code, $data);
    }

    public function createItem(string $name, string $description) {
        $data = json_encode(['name' => $name, 'description' => $description], JSON_UNESCAPED_UNICODE);
        $request = curl_init();
        curl_setopt_array(
            $request, 
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $this->apiURL.'/todo/',
                CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'Content-Length: '.strlen($data), 'JWT-Token: '.$this->jwtToken),
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data
            ));
        
        try {
            $data = curl_exec($request);
            $http_code = curl_getinfo($request, CURLINFO_RESPONSE_CODE);
        } finally {
            curl_close($request);
        }

        return new ResponseResult($http_code, $data);
    }

    public function itemRemove(int $id) {
        $request = curl_init();
        curl_setopt_array(
            $request, 
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $this->apiURL.'/todo/'.$id,
                CURLOPT_HTTPHEADER => array('JWT-Token: '.$this->jwtToken),
                CURLOPT_CUSTOMREQUEST => 'DELETE'
            ));
        
        try {
            $data = curl_exec($request);
            $http_code = curl_getinfo($request, CURLINFO_RESPONSE_CODE);
        } finally {
            curl_close($request);
        }

        return new ResponseResult($http_code, $data);
    }

    public function itemUpdate(int $id, string $name, string $description, bool $isDone) {
        $data = json_encode(['name' => $name, 'description' => $description, 'isDone' => $isDone], JSON_UNESCAPED_UNICODE);
        $request = curl_init();
        curl_setopt_array(
            $request, 
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $this->apiURL.'/todo/'.$id,
                CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'Content-Length: '.strlen($data), 'JWT-Token: '.$this->jwtToken),
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => $data
            ));
        
        try {
            $data = curl_exec($request);
            $http_code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        } finally {
            curl_close($request);
        }

        return new ResponseResult($http_code, $data);
    }
}

// $todoService = new TodoService("http://138.197.185.17", "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJyb2xlcyI6WyJST0xFX1VTRVIiLCJST0xFX0FETUlOIl0sInVzZXJuYW1lIjoieWtyb3BjaGlrIn0.YyHP88IInsa5bsbkX6Tmu3k7I7jtONwp-YHBcStU7bc");
// $result = $todoService->getTodoList();