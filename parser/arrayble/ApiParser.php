<?php

namespace restdoc\parser\arrayble;

use restdoc\parser\ApiParserInterface;
use restdoc\Utils;

/**
 * Class ApiParser
 */
class ApiParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?array
    {
        $content = Utils::trim($content);

        // Search: type, url and title
        // Example: {get} /user/:id Get User by ID.
        preg_match('/^(?:(?:\{(.+?)\})?\s*)?(.+?)(?:\s+(.+?))?$/iu', $content, $matches);

        if (!$matches) {
            return null;
        }

        if (!isset($matches[1])) {
            print_r($content);
            echo PHP_EOL, PHP_EOL;
        }

        return [
            'type' => $matches[1],
            'url' => $matches[2],
            'title' => $matches[3] ?? '',
        ];
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