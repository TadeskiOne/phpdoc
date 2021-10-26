<?php

namespace restdoc\parser\objectable;

use restdoc\parser\objectable\docElement\AbstractDocElement;

/**
 * Class ApiHeaderExampleParser
 * @package restdoc\parser\objectable
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