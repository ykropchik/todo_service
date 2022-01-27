<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TodoTest extends WebTestCase
{
    
    // public function testCreateUserWithValidData(): void
    // {
    //     $client = static::createClient();
    //     $crawler = $client->request(
    //         'POST', 
    //         '/registration',
    //         [],
    //         [],
    //         [],
    //         json_encode(['username' => "unitTest", 'password' => "unitTest"])
    //     );

    //     $request = $client->getRequest();
    //     echo $request;

    //     $this->assertResponseIsSuccessful();
    // }

    public function testInvalidData(): void
    {
        $this->expectOutputString("fuck this shit, i'm out");
        print((new User)->test(""));
    }

    public function testValidData(): void
    {
        $this->expectOutputString("NO, it's fucking ёбаный snow!");
        print ((new User)->test("my bitch is full of cocain, boy"));
    }
}
