<?php

namespace phpdoc\parser\objectable;

use phpdoc\parser\objectable\docElement\AbstractDocElement;
use phpdoc\parser\objectable\docElement\ApiName;
use phpdoc\Utils;

/**
 * Class ApiNameParser
 */
class ApiNameParser implements ApiParserInterface
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

        return (new ApiName())
            ->name(preg_replace('/(\s+)/iu','_', $name));
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