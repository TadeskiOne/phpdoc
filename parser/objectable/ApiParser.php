<?php

namespace phpdoc\parser\objectable;

use phpdoc\parser\objectable\docElement\AbstractDocElement;
use phpdoc\parser\objectable\docElement\Api;
use phpdoc\Utils;

/**
 * Class ApiParser
 */
class ApiParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?AbstractDocElement
    {
        $content = Utils::trim($content);

        // Search: type, url and title
        // Example: {get} /user/:id Get User by ID.
        preg_match_all('/^(?:(?:\{(.+?)\})?\s*)?(.+?)(?:\s+(.+?))?$/iu', $content, $matches);

        if (!$matches) {
            return null;
        }

        return (new Api())
            ->type($matches[1][0])
            ->url($matches[2][0])
            ->title($matches[3][0] ?? '');
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