<?php

namespace restdoc\parser\arrayble;

use restdoc\parser\ApiParserInterface;

/**
 * Class ApiQueryParser
 */
class ApiQueryParser extends AbstractParamParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?array
    {
        return $this->parser->parse($content, $source, 'Query');
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return 'local.query';
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }
}