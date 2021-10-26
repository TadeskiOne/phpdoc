<?php

namespace restdoc\parser\arrayble;

use restdoc\parser\ApiParserInterface;
use restdoc\Utils;

/**
 * Class ApiDeprecatedParser
 */
class ApiDeprecatedParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?array
    {
        $deprecated = Utils::trim($content);

        if ($deprecated === ''){
            return ['deprecated' => true];
        }

        return ['deprecated' => ['content' => $deprecated]];
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