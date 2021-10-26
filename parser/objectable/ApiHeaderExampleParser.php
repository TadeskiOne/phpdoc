<?php

namespace phpdoc\parser\objectable;

use phpdoc\parser\objectable\docElement\AbstractDocElement;

/**
 * Class ApiHeaderExampleParser
 * @package phpdoc\parser\objectable
 */
class ApiHeaderExampleParser extends AbstractExampleParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?AbstractDocElement
    {
        return $this->parser->parse($content, $content);
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return 'local.header.examples';
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }
}