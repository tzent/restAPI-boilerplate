<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Factories;


use App\Base\Helpers\Arr;
use Entities\AccessTokens;
use Entities\AuthorizationCodes;
use Entities\Jwts;
use Entities\OauthClients;
use Entities\RefreshTokens;
use Entities\Users;
use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\JwtBearer;
use OAuth2\GrantType\RefreshToken;
use OAuth2\GrantType\UserCredentials;
use OAuth2\ResponseType\AccessToken as AccessTokenResponse;
use OAuth2\ResponseType\AuthorizationCode as AuthorizationCodeResponse;
use OAuth2\Server;
use OAuth2\Storage\Memory;
use Slim\Container;

class Oauth2Server implements IFactory
{
    public static function create(Container $container)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em       = $container->get('em');
        $settings = Arr::get($container->get('settings'), 'auth', []);

        $client_storage             = $em->getRepository(OauthClients::class);
        $user_storage               = $em->getRepository(Users::class);
        $access_token_storage       = $em->getRepository(AccessTokens::class);
        $refresh_token_storage      = $em->getRepository(RefreshTokens::class);
        $authorization_code_storage = $em->getRepository(AuthorizationCodes::class);

        $auth_code_lifetime     = Arr::get($settings, 'auth_code_lifetime', 30);
        $access_lifetime        = Arr::get($settings, 'access_lifetime', 3600);
        $refresh_token_lifetime = Arr::get($settings, 'refresh_token_lifetime', 7200);

        $server = new Server([
            'client_credentials' => $client_storage,
            'user_credentials'   => $user_storage,
            'access_token'       => $access_token_storage,
            'refresh_token'      => $refresh_token_storage,
            'authorization_code' => $authorization_code_storage
        ], [
            'auth_code_lifetime'             => $auth_code_lifetime,
            'access_lifetime'                => $access_lifetime,
            'refresh_token_lifetime'         => $refresh_token_lifetime,
            'always_issue_new_refresh_token' => true,
            'use_jwt_access_tokens'          => true
        ], [
            new ClientCredentials($client_storage),
            new UserCredentials($user_storage),
            new RefreshToken($refresh_token_storage, [
                'always_issue_new_refresh_token' => true
            ]),
            new AuthorizationCode($authorization_code_storage)
        ], [
            new AccessTokenResponse($access_token_storage, $refresh_token_storage, [
                'access_lifetime'        => $access_lifetime,
                'refresh_token_lifetime' => $refresh_token_lifetime,
            ]),
            new AuthorizationCodeResponse($authorization_code_storage, [
                'auth_code_lifetime' => $authorization_code_storage
            ])
        ]);

        $storage = [];
        foreach ($em->getRepository(Jwts::class)->findAll() as $jwt) {
            $client = $jwt->getClient();
            $storage[$client->getId()] = [
                'subject' => $jwt->getSubject(),
                'key'     => $jwt->getPublicKey()
            ];
        }

        if (!empty($storage)) {
            $server->addGrantType(new JwtBearer(new Memory(['jwt' => $storage]), 'http://rest.dev'));
        }

        return $server;
    }
}