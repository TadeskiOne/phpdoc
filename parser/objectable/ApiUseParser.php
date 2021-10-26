<?php

namespace restdoc\parser\objectable;

use restdoc\parser\objectable\docElement\AbstractDocElement;
use restdoc\parser\objectable\docElement\ApiUse;
use restdoc\Utils;

/**
 * Class ApiUseParser
 */
class ApiUseParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?AbstractDocElement
    {
        $name = Utils::trim($content);

        if ($name === '') {
            return null;
        }

        return (new ApiUse())
            ->name($name);
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