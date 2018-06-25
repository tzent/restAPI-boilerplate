<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Security;

use App\Base\Helpers\Arr;
use App\Base\Security\Annotations\Granted;
use App\Base\Security\Exceptions\SecurityException;
use Entities\AccessTokens;
use Slim\Container;

class Guard
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Firewall constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param \ReflectionClass $reflector
     * @throws SecurityException
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function granted(\ReflectionClass $reflector)
    {
        foreach ($this->container->get('em')->getConfiguration()->getMetadataDriverImpl()->getReader()->getClassAnnotations($reflector) as $annotation) {
            if ($annotation instanceof Granted) {
                $this->check($annotation);
                break;
            }
        }
    }

    /**
     * @throws SecurityException
     * @throws \Interop\Container\Exception\ContainerException
     */
    private function check(Granted $granted)
    {
        $headers  = $this->container->get('request')->getHeaders();
        $settings = Arr::get($this->container->get('settings'), 'oauth', []);
        $token    = $this->container->get('em')->getRepository(AccessTokens::class)->findOneByAccessToken(
            str_replace(
                sprintf('%s ', Arr::get($settings, 'token_type', '')),
                '',
                Arr::get($headers, 'HTTP_AUTHORIZATION', '')
            )
        );

        if (!$token || time() > $token->getExpires()->getTimestamp()) {
            throw new SecurityException('', 401);
        }

        $client = $token->getClient();
        if (!$client->isPublic() && !$client->checkClientCredentials($client->getId(), Arr::get($headers, 'PHP_AUTH_USER'))) {
            throw new SecurityException('', 401);
        }

//        ToDo check granted validators
//        foreach ($granted->getValidators() as $validator) {
//            if (false) {
//                throw new SecurityException('', 401);
//            }
//        }

        $this->container['token'] = $token;
    }
}