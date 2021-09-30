#!/usr/bin/env
<?php

class TodoService {

    public function registration(string $username, string $password) {
        $data = json_encode(['username' => $username, 'password' => $password], JSON_UNESCAPED_UNICODE);
        $request = curl_init();
        curl_setopt_array(
            $request, 
            array(
                CURLOPT_URL => 'http://138.197.185.17/registration',
                CURLOPT_HTTPHEADER => array('Content-type: application/json', 'Content-Length: '.strlen($data)),
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data
            ));
        $result = curl_exec($request);
        curl_close($request);
        return $result;
    }

    public function auth(string $username, string $password) {
        $data = json_encode(['username' => $username, 'password' => $password], JSON_UNESCAPED_UNICODE);
        $request = curl_init();
        curl_setopt_array(
            $request, 
            array(
                CURLOPT_URL => 'http://138.197.185.17/api/login_check',
                CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'Content-Length: '.strlen($data)),
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data
            ));
        $result = curl_exec($request);
        curl_close($request);
        return $result;
    }

    public function getTodoList(string $jwt) {
        $request = curl_init();
        curl_setopt_array(
            $request, 
            array(
                CURLOPT_URL => 'http://138.197.185.17/todo/',
                CURLOPT_HTTPHEADER => array('JWT-Token: '.$jwt),
                CURLOPT_CUSTOMREQUEST => 'GET'
            ));
        $result = curl_exec($request);
        curl_close($request);
        return $result;
    }

    public function createItem(string $jwt, string $name, string $description) {
        $data = json_encode(['name' => $name, 'description' => $description], JSON_UNESCAPED_UNICODE);
        $request = curl_init();
        curl_setopt_array(
            $request, 
            array(
                CURLOPT_URL => 'http://138.197.185.17/todo/',
                CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'Content-Length: '.strlen($data), 'JWT-Token: '.$jwt),
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data
            ));
        $result = curl_exec($request);
        curl_close($request);
        return $result;
    }

    public function itemRemove(string $jwt, int $id) {
        $request = curl_init();
        curl_setopt_array(
            $request, 
            array(
                CURLOPT_URL => 'http://138.197.185.17/todo/'.$id,
                CURLOPT_HTTPHEADER => array('JWT-Token: '.$jwt),
                CURLOPT_CUSTOMREQUEST => 'DELETE'
            ));
        $result = curl_exec($request);
        curl_close($request);
        return $result;
    }

    public function itemUpdate(int $id, string $name, string $description, bool $isDone) {
        $data = json_encode(['name' => $name, 'description' => $description, 'isDone' => $isDone], JSON_UNESCAPED_UNICODE);
        $request = curl_init();
        curl_setopt_array(
            $request, 
            array(
                CURLOPT_URL => 'http://138.197.185.17/todo/'.$id,
                CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'Content-Length: '.strlen($data)),
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => $data
            ));
        $result = curl_exec($request);
        curl_close($request);
        return $result;
    }
}