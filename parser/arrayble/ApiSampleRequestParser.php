<?php

namespace phpdoc\parser\arrayble;

use phpdoc\parser\ApiParserInterface;
use phpdoc\Utils;

/**
 * Class ApiUrlParser
 */
class ApiSampleRequestParser implements ApiParserInterface
{

    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?array
    {
        $url = Utils::trim($content);

        if ($url === '') {
            return null;
        }

        return ['url' => $url];
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