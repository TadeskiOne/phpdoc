<?php

namespace phpdoc\parser\arrayble;

use phpdoc\parser\ApiParserInterface;

/**
 * Class ApiHeaderParser
 */
class ApiHeaderParser extends AbstractParamParser implements ApiParserInterface
{
    /**
     * @param string $content
     * @param string $source
     * @return array|null
     */
    public function parse(string $content, string $source): ?array
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