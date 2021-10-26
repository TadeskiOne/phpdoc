<?php

namespace phpdoc\parser\objectable;

use phpdoc\parser\objectable\docElement\AbstractDocElement;

/**
 * Class ApiParamsExampleParser
 */
class ApiParamExampleParser extends AbstractExampleParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?AbstractDocElement
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