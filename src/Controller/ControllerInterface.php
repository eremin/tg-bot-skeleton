<?php

namespace BotNamespace\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ControllerInterface
{
    public const NAMESPACE = __NAMESPACE__;
    public const DIR = __DIR__;
    public function supports(RequestInterface $request): bool;
    public function response(RequestInterface $request): ResponseInterface;
}