<?php

namespace restdoc\parser\objectable;

use restdoc\parser\objectable\docElement\AbstractDocElement;
use restdoc\parser\objectable\docElement\ApiDescription;
use restdoc\Utils;

class ApiDescriptionParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?AbstractDocElement
    {
        $description = Utils::trim($content);

        if ($description === '') {
            return null;
        }

        return (new ApiDescription())
            ->description(Utils::unindent($description));
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
