<?php

namespace BotNamespace\Controller;

use Laminas\Diactoros\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use TgBotApi\BotApiBase\BotApiComplete;
use TgBotApi\BotApiBase\Exception\BadArgumentException;
use TgBotApi\BotApiBase\Exception\ResponseException;
use TgBotApi\BotApiBase\Method\SetWebhookMethod;

class SetController implements ControllerInterface
{
    private BotApiComplete $botApiComplete;
    private StreamFactoryInterface $streamFactory;
    private string $botUrl;

    public function __construct(BotApiComplete $botApiComplete, StreamFactoryInterface $streamFactory, string $botUrl)
    {
        $this->botApiComplete = $botApiComplete;
        $this->streamFactory = $streamFactory;
        $this->botUrl = $botUrl;
    }

    public function supports(RequestInterface $request): bool
    {
        return $request->getUri()->getPath() === '/set';
    }

    /**
     * @throws ResponseException|BadArgumentException
     */
    public function response(RequestInterface $request): ResponseInterface
    {
        if ($this->botApiComplete->setWebhook(SetWebhookMethod::create($this->botUrl))) {
            return new Response($this->streamFactory->createStream(
                'OK'
            ));
        }

        return new Response($this->streamFactory->createStream(
            'Failed'
        ), 500);
    }
}