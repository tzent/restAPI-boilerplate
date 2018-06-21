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
use Slim\Container;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator as TranslationHandler;

class Translator implements IFactory
{
    public static function create(Container $container)
    {
        $settings   = Arr::get($container->get('settings'), 'translation', []);
        $translator = new TranslationHandler(
            $container->get('request')->getParsedBodyParam('locale', Arr::get($settings, 'default')),
            new MessageSelector()
        );
        $translator->addLoader('php', new PhpFileLoader());

        foreach (new \DirectoryIterator(Arr::get($settings, 'resources_path')) as $file) {
            if($file->isFile() && !strcmp($file->getExtension(), 'php')) {
                $translator->addResource(
                    $file->getExtension(),
                    $file->getRealPath(),
                    $file->getBasename(sprintf('.%s', $file->getExtension()))
                );
            }
        }

        return $translator;
    }
}