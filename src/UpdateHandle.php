<?php
namespace BotNamespace;

use TgBotApi\BotApiBase\BotApiComplete;
use TgBotApi\BotApiBase\Type\UpdateType;

class UpdateHandle
{
    private BotApiComplete $api;

    public function __construct(BotApiComplete $api)
    {
        $this->api = $api;
    }

    public function handle(UpdateType $update): void
    {
        // todo place your code here
    }
}
