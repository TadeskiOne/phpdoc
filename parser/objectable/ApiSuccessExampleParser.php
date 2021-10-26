<?php

namespace restdoc\parser\objectable;

use restdoc\parser\objectable\docElement\AbstractDocElement;
use restdoc\parser\objectable\docElement\ApiSuccessExample;
use restdoc\Utils;

/**
 * Class ApiSuccessExampleParser
 * @package restdoc\parser\objectable
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