<?php

namespace phpdoc\parser\arrayble;

use phpdoc\parser\ApiParserInterface;

/**
 * Class ApiPermissionParser
 */
class ApiPermissionParser implements ApiParserInterface
{
    /**
     * @var ApiUseParser
     */
    protected $parser;

    /**
     * AbstractParser constructor.
     * @param ApiUseParser $parser
     */
    public function __construct(ApiUseParser $parser) {
        $this->parser = $parser;
    }

    /**
     * @inheritDoc
     */
    public function parse(string $content, ?string $source = null): ?array
    {
        return $this->parser->parse($content, $source);
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
    public function isDeprecated(): bool
    {
        return false;
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
}