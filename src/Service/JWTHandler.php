<?php

namespace App\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler
{
    private string $secret;

    public function __construct()
    {
        $this->secret = $_ENV['JWT_SECRET'];
    }

    public function generateToken(array $payload, int $expiry = 7200): string
    {
        $payload['exp'] = time() + $expiry;

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function decodeToken(string $token): ?array
    {
        try {
            return (array) JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }
}