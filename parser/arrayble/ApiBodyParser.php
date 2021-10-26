<?php

namespace phpdoc\parser\arrayble;


use phpdoc\parser\ApiParserInterface;

/**
 * Class ApiBodyParser
 */
class ApiBodyParser extends AbstractParamParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?array
    {
        return $this->parser->parse($content, $source, 'Body');
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return 'local.body';
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }
}
