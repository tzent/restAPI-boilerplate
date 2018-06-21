<?php

define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);

include ROOT_PATH . 'vendor/autoload.php';

function generateJWT($privateKey, $iss, $sub, $aud, $exp = null, $nbf = null, $jti = null)
{
    if (!$exp) {
        $exp = time() + 1000;
    }

    $params = array(
        'iss' => $iss,
        'sub' => $sub,
        'aud' => $aud,
        'exp' => $exp,
        'iat' => time(),
    );

    if ($nbf) {
        $params['nbf'] = $nbf;
    }

    if ($jti) {
        $params['jti'] = $jti;
    }

    $jwtUtil = new OAuth2\Encryption\Jwt();

    return $jwtUtil->encode($params, $privateKey, 'RS256');
}

$private_key = openssl_get_privatekey('file://' . ROOT_PATH . 'keys/private.pem', '1234');
$client_id   = 'afd0d59a-5d0a-11e8-97d0-080027ded765';
$user_id     = '8753ef1d-5d0a-11e8-97d0-080027ded765';
$grant_type  = 'urn:ietf:params:oauth:grant-type:jwt-bearer';

$jwt = generateJWT($private_key, $client_id, $user_id, 'http://rest.dev');

passthru(sprintf('curl -d "grant_type=%s&assertion=%s" -H "Content-Type: application/x-www-form-urlencoded" -X POST http://rest.dev/signin', $grant_type, $jwt));

openssl_free_key($private_key);
