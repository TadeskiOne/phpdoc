<?php

namespace restdoc\parser\objectable;

use restdoc\parser\objectable\docElement\AbstractDocElement;

/**
 * Class ApiBodyParser
 */
class ApiBodyParser extends AbstractParamParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?AbstractDocElement
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
