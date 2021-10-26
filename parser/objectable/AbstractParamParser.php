<?php

namespace phpdoc\parser\objectable;

/**
 * Class AbstractParser
 */
abstract class AbstractParamParser implements ApiParserInterface
{
    /**
     * @var bool
     */
    protected $preventGlobal = false;

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
     * @return bool
     */
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