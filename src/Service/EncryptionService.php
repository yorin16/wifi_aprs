<?php

namespace App\Service;

class EncryptionService
{
    function encryptData($data): string
    {
        $cipher = "aes-256-cbc";
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $encrypted = openssl_encrypt($data, $cipher, getenv('ENCRYPTION_SERVICE_KEY'), 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    function decryptData($data): false|string
    {
        $cipher = "aes-256-cbc";
        $data = base64_decode($data);
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = substr($data, 0, $ivlen);
        $data = substr($data, $ivlen);
        return openssl_decrypt($data, $cipher, getenv('ENCRYPTION_SERVICE_KEY'), 0, $iv);
    }
}