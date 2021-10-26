<?php

namespace phpdoc\parser\objectable;

use phpdoc\parser\objectable\docElement\AbstractDocElement;

/**
 * Class ApiErrorParser
 */
class ApiErrorParser extends AbstractParamParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?AbstractDocElement
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