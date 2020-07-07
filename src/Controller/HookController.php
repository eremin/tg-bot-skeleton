<?php

namespace BotNamespace\Controller;

use BotNamespace\UpdateHandle;
use Laminas\Diactoros\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use TgBotApi\BotApiBase\Exception\BadRequestException;
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

    /**
     * @throws BadRequestException
     */
    public function response(RequestInterface $request): ResponseInterface
    {
        $updateType = $this->fetcher->fetch($request);
        $this->updateHandle->handle($updateType);

        return new Response();
    }
}