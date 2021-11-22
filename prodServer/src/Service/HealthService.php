<?php

namespace App\Service;

class HealthService
{
    private $envName;

    public function __construct($envName)
    {
        $this->envName = $envName;
    }

    public function getEnvName() {
        return $this->envName;
    }
}