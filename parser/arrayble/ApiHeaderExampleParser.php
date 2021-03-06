<?php

namespace phpdoc\parser\arrayble;

use phpdoc\parser\ApiParserInterface;

/**
 * Class ApiHeaderExampleParser
 * @package phpdoc\parser\arrayble
 */
class ApiHeaderExampleParser extends AbstractExampleParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?array
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