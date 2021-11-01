<?php

namespace Client;

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