<?php

namespace phpdoc\parser\objectable;

use phpdoc\parser\objectable\docElement\AbstractDocElement;
use phpdoc\parser\objectable\docElement\ApiSampleRequest;
use phpdoc\Utils;

/**
 * Class ApiUrlParser
 */
class ApiSampleRequestParser implements ApiParserInterface
{

    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?AbstractDocElement
    {
        $url = Utils::trim($content);

        if ($url === '') {
            return null;
        }

        return (new ApiSampleRequest())
            ->url($url);
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return 'local.sampleRequest';
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