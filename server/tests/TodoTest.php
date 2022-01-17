<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TodoTest extends WebTestCase
{
    public function testCreateUserWithValidData(): void
    {
        $client = static::createClient();
        $crawler = $client->request(
            'POST', 
            '/registration',
            [],
            [],
            [],
            json_encode(['username' => "unitTest", 'password' => "unitTest"])
        );

        $request = $client->getRequest();
        echo $request;

        $this->assertResponseIsSuccessful();
    }
}
