<?php

namespace BotNamespace\Controller;

use Laminas\Diactoros\Response;
use BotNamespace\UpdateHandle;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use TgBotApi\BotApiBase\WebhookFetcher;

class HookController implements ControllerInterface
{
    private WebhookFetcher $fetcher;
    private UpdateHandle $updateHandle;

    public function __construct(WebhookFetcher $fetcher, UpdateHandle $updateHandle)
    {
        $this->fetcher = $fetcher;
        $this->updateHandle = $updateHandle;
    }

    public function supports(RequestInterface $request): bool
    {
        return $request->getUri()->getPath() === '/hook';
    }

    public function response(RequestInterface $request): ResponseInterface
    {
        $updateType = $this->fetcher->fetch($request);
        $this->updateHandle->handle($updateType);

        return new Response();
    }
}