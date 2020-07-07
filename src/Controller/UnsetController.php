<?php

namespace BotNamespace\Controller;

use Laminas\Diactoros\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use TgBotApi\BotApiBase\BotApiComplete;
use TgBotApi\BotApiBase\Exception\ResponseException;
use TgBotApi\BotApiBase\Method\DeleteWebhookMethod;

class UnsetController implements ControllerInterface
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
        return $request->getUri()->getPath() === '/unset';
    }

    /**
     * @throws ResponseException
     */
    public function response(RequestInterface $request): ResponseInterface
    {
        if ($this->botApiComplete->deleteWebhook(DeleteWebhookMethod::create())) {
            return new Response($this->streamFactory->createStream(
                'OK'
            ));
        }

        return new Response($this->streamFactory->createStream(
            'Failed'
        ), 500);
    }
}