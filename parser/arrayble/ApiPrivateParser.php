<?php

namespace phpdoc\parser\arrayble;

use phpdoc\parser\ApiParserInterface;

/**
 * Class ApiPrivateParser
 */
class ApiPrivateParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?array
    {
        return ['private' => true];
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return 'local';
    }

    /**
     * @return bool
     */
    public function isPreventGlobal(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return 'insert';
    }

    /**
     * @return bool
     */
    public function isExtendRoot(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }
}