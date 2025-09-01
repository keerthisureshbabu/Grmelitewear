<?php
/**
 * JWT Library for PHP
 * Simple JWT implementation without external dependencies
 */

class JWT {
    private static $algorithms = [
        'HS256' => 'sha256',
        'HS384' => 'sha384',
        'HS512' => 'sha512'
    ];

    /**
     * Encode JWT
     */
    public static function encode($payload, $key, $algorithm = 'HS256') {
        $header = [
            'typ' => 'JWT',
            'alg' => $algorithm
        ];

        $headerEncoded = self::base64UrlEncode(json_encode($header));
        $payloadEncoded = self::base64UrlEncode(json_encode($payload));

        $signature = hash_hmac(self::$algorithms[$algorithm], 
            $headerEncoded . '.' . $payloadEncoded, 
            $key, 
            true
        );
        $signatureEncoded = self::base64UrlEncode($signature);

        return $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
    }

    /**
     * Decode JWT
     */
    public static function decode($jwt, $key, $algorithm = 'HS256') {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            throw new Exception('Invalid JWT format');
        }

        list($headerEncoded, $payloadEncoded, $signatureEncoded) = $parts;

        $header = json_decode(self::base64UrlDecode($headerEncoded), true);
        $payload = json_decode(self::base64UrlDecode($payloadEncoded), true);

        if (!$header || !$payload) {
            throw new Exception('Invalid JWT data');
        }

        // Verify algorithm
        if ($header['alg'] !== $algorithm) {
            throw new Exception('Algorithm mismatch');
        }

        // Verify signature
        $signature = self::base64UrlDecode($signatureEncoded);
        $expectedSignature = hash_hmac(self::$algorithms[$algorithm], 
            $headerEncoded . '.' . $payloadEncoded, 
            $key, 
            true
        );

        if (!hash_equals($signature, $expectedSignature)) {
            throw new Exception('Invalid signature');
        }

        // Check expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            throw new Exception('Token expired');
        }

        return $payload;
    }

    /**
     * Base64 URL encode
     */
    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Base64 URL decode
     */
    private static function base64UrlDecode($data) {
        $base64 = strtr($data, '-_', '+/');
        $pad = strlen($base64) % 4;
        if ($pad) {
            $base64 .= str_repeat('=', 4 - $pad);
        }
        return base64_decode($base64);
    }
}
?>
