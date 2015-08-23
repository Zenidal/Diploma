<?php

namespace AppBundle\Helpers;

class ApiKeyGenerator
{
    public static function generateApiKey()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $apiKey = null;
        for ($i = 0; $i < 64; $i++) {
            $apiKey .= $characters[rand(0, strlen($characters) - 1)];
        }
        return base64_encode(sha1(uniqid('ue' . rand(rand(), rand())) . $apiKey));
    }
}