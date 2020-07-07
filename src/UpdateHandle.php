<?php
namespace BotNamespace;

use Spiral\Debug\Dumper;
use TgBotApi\BotApiBase\BotApiComplete;
use TgBotApi\BotApiBase\Exception\ResponseException;
use TgBotApi\BotApiBase\Method\AnswerInlineQueryMethod;
use TgBotApi\BotApiBase\Type\InlineQueryResult\InlineQueryResultArticleType;
use TgBotApi\BotApiBase\Type\InputMessageContent\InputTextMessageContentType;
use TgBotApi\BotApiBase\Type\UpdateType;

class UpdateHandle
{
    private ManualDictionarySearch $dictionary;
    private BotApiComplete $api;

    public function __construct(ManualDictionarySearch $dictionary, BotApiComplete $api)
    {
        $this->dictionary = $dictionary;
        $this->api = $api;
    }

    /**
     * @throws ResponseException
     */
    public function handle(UpdateType $update): void
    {
        $inlineQuery = $update->inlineQuery;
        if (null !== $inlineQuery && mb_strlen($inlineQuery->query) >= 3) {
            $results = array_map(
                fn(ManualEntity $manualEntity) => $this->mapResultsCallback($manualEntity),
                $this->dictionary->find($inlineQuery->query)
            );

            try {
                $this->api->answer(AnswerInlineQueryMethod::create($inlineQuery->id, array_values($results), [
                    'cacheTime' => 1,
                ]));
            } catch (ResponseException $exception) {
                $this->dumper->dump($inlineQuery, Dumper::ERROR_LOG);
                $this->dumper->dump($exception, Dumper::ERROR_LOG);
            }
        }
    }

    public function mapResultsCallback(ManualEntity $manualEntity): InlineQueryResultArticleType
    {
        return InlineQueryResultArticleType::create(
            $manualEntity->getKey(),
            $manualEntity->getTopic(),
            InputTextMessageContentType::create(
                <<<HTML
                <b>{$manualEntity->getTopic()}</b>
                {$manualEntity->getDescription()}
                http://php.net/manual/en/{$manualEntity->getKey()}.php
                HTML,
                ['parseMode' => 'HTML']
            ),
            ['description' => $manualEntity->getDescription()]
        );
    }
}
