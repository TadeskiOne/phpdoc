<?php

namespace phpdoc\parser\objectable;

/**
 * Class AbstractExampleParser
 */
abstract class AbstractExampleParser implements ApiParserInterface
{
    /**
     * @var bool
     */
    protected $preventGlobal = false;

    /**
     * @var ApiExampleParser
     */
    protected $parser;

    /**
     * AbstractExampleParser constructor.
     * @param ApiExampleParser $parser
     */
    public function __construct(ApiExampleParser $parser)
    {
        $this->parser = $parser;
    }

    public function isPreventGlobal(): bool
    {
        return $this->preventGlobal;
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