<?php

namespace restdoc\parser\arrayble;

use restdoc\parser\ApiParserInterface;
use restdoc\Utils;

/**
 * Class ApiNameParser
 */
class ApiNameParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?array
    {
        $name = Utils::trim($content);

        if ($name === '') {
            return null;
        }

        return ['name' => preg_replace('/(\s+)/iu','_', $name)];
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