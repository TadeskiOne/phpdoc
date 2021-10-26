<?php

namespace restdoc\parser\arrayble;

use restdoc\parser\ApiParserInterface;

/**
 * Class ApiParamsExampleParser
 */
class ApiParamExampleParser extends AbstractExampleParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?array
    {
        return $this->parser->parse($content, $source);
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return 'local.parameter.examples';
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }
}