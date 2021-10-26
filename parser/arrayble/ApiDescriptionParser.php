<?php

namespace restdoc\parser\arrayble;

use restdoc\parser\ApiParserInterface;
use restdoc\Utils;

/**
 * Class ApiDescriptionParser
 */
class ApiDescriptionParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?array
    {
        $description = Utils::trim($content);

        if ($description === '') {
            return null;
        }

        return ['description' => Utils::unindent($description)];
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
