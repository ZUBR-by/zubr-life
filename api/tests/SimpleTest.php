<?php

namespace Tests;

use App\TelegramAuthentication;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class SimpleTest extends KernelTestCase
{
    public function testSomething()
    {
        self::bootKernel();
        $auth    = self::$container->get(TelegramAuthentication::class);
        $encoder = self::$container->get(JWTEncoderInterface::class);
        $data    = ['id' => 1, 'test' => 1];
        $request = new Request(
            [],
            [],
            [],
            [
                'AUTH_TOKEN' => $encoder->encode($data),
            ]
        );
        $this->assertEquals($data, array_intersect($data, $auth->getCredentials($request)));
    }
}
