<?php

namespace phpdoc\parser\arrayble;

use phpdoc\parser\ApiParserInterface;
use phpdoc\Utils;

/**
 * Class ApiExampleParser
 */
class ApiExampleParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?array
    {
        $source = Utils::trim($source);

        $title = '';
        $text = '';
        $type = '';

        // Search for @apiExample "[{type}] title and content
        // /^(@\w*)?\s?(?:(?:\{(.+?)\})\s*)?(.*)$/gm;
        $parseRegExpFirstLine = '/(@\w*)?(?:(?:\s*\{\s*([a-zA-Z0-9\.\/\\\[\]_-]+)\s*\}\s*)?\s*(.*)?)?/';
        $parseRegExpFollowing = '/(^.*\s?)/uim';

        preg_match($parseRegExpFirstLine, $source, $matches);
        if ($matches) {
            $type = $matches[2];
            $title = $matches[3];
        }

        preg_match($parseRegExpFollowing, $source, $matches);
        $text = $matches[1];

        if ($text === '') {
            return null;
        }

        return [
            'title' => $title,
            'content' => Utils::unindent($text),
            'type' => $type ?: 'json',
        ];
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return 'local.examples';
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