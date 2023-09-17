<?php

namespace Vendon\core;

use ReallySimpleJWT\Token;

class Authorization
{
    /**
     * @var string
     */
    public string $secret = 'a7fbc238e!ReT423*&';

    /**
     * @param $userId
     * @return string
     */
    //vvv Generate JWT for authorization
    public function generateToken($userId): string
    {
        $secret = $this->secret;
        $expiration = time() + 3600;
        $issuer = 'localhost';
        return Token::create($userId, $secret, $expiration, $issuer);
    }

    /**
     * @param $token
     * @return bool
     */
    //vvv Validate JWT for authorization
    public function validateToken($token): bool
    {
        $secret = $this->secret;
        return Token::validate($token, $secret);
    }

    /**
     * @param $token
     * @return array
     */
    //vvv Get JWT payload
    public function getPayload($token): array
    {
        return Token::getPayload($token);
    }
}