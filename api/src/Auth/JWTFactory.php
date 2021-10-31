<?php

namespace App\Auth;

use Firebase\JWT\JWT;
use function Psl\Filesystem\read_file;

class JWTFactory
{
    private string $privateKey;
    private string $publicKey;
    private string $jwtAlgo;

    public function __construct(string $privateKey, string $publicKey, string $jwtAlgo)
    {
        $this->privateKey = $privateKey;
        $this->publicKey  = $publicKey;
        $this->jwtAlgo    = $jwtAlgo;
    }

    public function encode(array $payload): string
    {
        return JWT::encode($payload, read_file($this->privateKey), $this->jwtAlgo);
    }

    public function decode(string $jwt): array
    {
        return (array)JWT::decode(
            $jwt,
            read_file($this->publicKey),
            [$this->jwtAlgo]
        );
    }
}
