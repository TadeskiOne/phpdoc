<?php

namespace phpdoc\parser\objectable;

use phpdoc\parser\objectable\docElement\AbstractDocElement;

/**
 * Class ApiSuccessParser
 */
class ApiSuccessParser extends AbstractParamParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?AbstractDocElement
    {
        return $this->parser->parse($content, $source, 'Success 200');
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return 'local.success.fields.' . $this->parser->getGroup();
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }
}