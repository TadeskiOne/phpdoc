<?php

namespace phpdoc\parser\objectable;

use phpdoc\parser\objectable\docElement\AbstractDocElement;

/**
 * Class ApiHeaderParser
 */
class ApiHeaderParser extends AbstractParamParser implements ApiParserInterface
{
    /**
     * @param string $content
     * @param string $source
     * @return AbstractDocElement|null
     */
    public function parse(string $content, string $source): ?AbstractDocElement
    {
        return $this->parser->parse($content, $source, 'Header');
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return 'local.header.fields.' . $this->parser->getGroup();
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }
}