<?php

namespace phpdoc\parser\objectable;

use phpdoc\parser\objectable\docElement\AbstractDocElement;
use phpdoc\parser\objectable\docElement\ApiSuccessExample;
use phpdoc\Utils;

/**
 * Class ApiSuccessExampleParser
 * @package phpdoc\parser\objectable
 */
class ApiSuccessExampleParser extends AbstractExampleParser implements ApiParserInterface
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

        return (new ApiSuccessExample())
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
    public function isDeprecated(): bool
    {
        return false;
    }
}