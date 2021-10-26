<?php

namespace restdoc\parser\arrayble;

use restdoc\parser\ApiParserInterface;

/**
 * Class ApiErrorParser
 */
class ApiErrorParser extends AbstractParamParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?array
    {
        return $this->parser->parse($content, $source, 'Error 4xx');
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return 'local.error.fields.' . $this->parser->getGroup();
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }
}