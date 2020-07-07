<?php

use BotNamespace\Controller\ControllerInterface;
use BotNamespace\Controller\SetController;
use BotNamespace\Router;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Spiral\Debug\Dumper;
use Spiral\Debug\Renderer\ConsoleRenderer;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TgBotApi\BotApiBase\ApiClient;
use TgBotApi\BotApiBase\ApiClientInterface;
use TgBotApi\BotApiBase\BotApi;
use TgBotApi\BotApiBase\BotApiComplete;
use TgBotApi\BotApiBase\BotApiNormalizer;

use TgBotApi\BotApiBase\NormalizerInterface;

use function DI\autowire;
use function DI\env;
use function DI\get;

return [
    'apiKey' => env('API_KEY'),
    'botUrl' => env('BOT_URL'),

    RequestFactoryInterface::class => static fn(ContainerInterface $container) => $container->get(Psr18Client::class),
    StreamFactoryInterface::class => static fn(ContainerInterface $container) => $container->get(Psr18Client::class),
    ClientInterface::class => static fn(ContainerInterface $container) => $container->get(Psr18Client::class),

    ApiClientInterface::class => static fn(ContainerInterface $container) => $container->get(ApiClient::class),
    NormalizerInterface::class => static fn(ContainerInterface $container) => $container->get(BotApiNormalizer::class),

    BotApi::class => autowire()
        ->constructorParameter('botKey', get('apiKey')),
    BotApiComplete::class => autowire()
        ->constructorParameter('botKey', get('apiKey')),

    HttpClientInterface::class => fn() => HttpClient::create(),


    Dumper::class => fn(ConsoleRenderer $renderer) => (new Dumper())->setRenderer(Dumper::ERROR_LOG, $renderer),

    SetController::class => autowire()
        ->constructorParameter('botUrl', get('botUrl')),

    Router::class => static function (StreamFactoryInterface $streamFactory, ContainerInterface $container) {
        $inject = [];
        foreach (glob(ControllerInterface::DIR . '/*Controller.php') as $controller) {
            $controller = basename($controller, '.php');
            $object = $container->get(ControllerInterface::NAMESPACE . '\\' . $controller);
            if ($object instanceof ControllerInterface) {
                $inject[] = $object;
            }
        }
        return new Router($streamFactory, ...$inject);
    },
];
