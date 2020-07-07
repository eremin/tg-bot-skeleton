<?php

namespace BotNamespace;

use Laminas\Diactoros\Response;
use BotNamespace\Controller\ControllerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Router
{
    private StreamFactoryInterface $streamFactory;
    /**
     * @var ControllerInterface[]
     */
    private array $controllers;

    public function __construct(StreamFactoryInterface $streamFactory, ControllerInterface ...$controllers)
    {
        $this->streamFactory = $streamFactory;
        $this->controllers = $controllers;
    }

    public function handle(RequestInterface $request): ResponseInterface
    {
        foreach ($this->controllers as $controller) {
            if ($controller->supports($request)) {
                return $controller->response($request);
            }
        }

        ob_start();
        var_dump($request);

        return new Response(
            $this->streamFactory->createStream('<h1>404 Not Found</h1><pre>' . ob_get_clean() . '</pre>'),
            404
        );
    }
}