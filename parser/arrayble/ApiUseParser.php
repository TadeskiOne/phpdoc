<?php

namespace phpdoc\parser\arrayble;

use phpdoc\parser\ApiParserInterface;
use phpdoc\Utils;

/**
 * Class ApiUseParser
 */
class ApiUseParser implements ApiParserInterface
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

        return ['name' => $name];
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return 'local.use';
    }

    /**
     * @return bool
     */
    public function isPreventGlobal(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return 'push';
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