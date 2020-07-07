<?php

namespace BotNamespace\Controller;

use Laminas\Diactoros\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use TgBotApi\BotApiBase\BotApiComplete;
use TgBotApi\BotApiBase\Method\GetWebhookInfoMethod;
use TgBotApi\BotApiBase\Method\SetWebhookMethod;

class InfoController implements ControllerInterface
{
    private BotApiComplete $botApiComplete;
    private StreamFactoryInterface $streamFactory;

    public function __construct(BotApiComplete $botApiComplete, StreamFactoryInterface $streamFactory)
    {
        $this->botApiComplete = $botApiComplete;
        $this->streamFactory = $streamFactory;
    }

    public function supports(RequestInterface $request): bool
    {
        return $request->getUri()->getPath() === '/info';
    }

    public function response(RequestInterface $request): ResponseInterface
    {
        return new Response($this->streamFactory->createStream(
            var_export($this->botApiComplete->getWebhookInfo(GetWebhookInfoMethod::create()), true)
        ));
    }
}