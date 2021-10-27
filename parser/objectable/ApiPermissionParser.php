<?php

namespace phpdoc\parser\objectable;

use phpdoc\parser\objectable\docElement\AbstractDocElement;

/**
 * Class ApiPermissionParser
 */
class ApiPermissionParser implements ApiParserInterface
{
    /**
     * @var ApiParamParser
     */
    protected $parser;

    /**
     * AbstractParser constructor.
     * @param ApiParamParser $parser
     */
    public function __construct(ApiParamParser $parser) {
        $this->parser = $parser;
    }

    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source, string $defaultGroup = 'Parameter'): ?AbstractDocElement
    {
        return $this->parser->parse($content, $source, $defaultGroup);
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return 'local.permission';
    }
    /**
     * @return bool
     */
    public function isPreventGlobal(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->parser->getMethod();
    }

    /**
     * @return bool
     */
    public function isExtendRoot(): bool
    {
        return false;
    }
    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }
}