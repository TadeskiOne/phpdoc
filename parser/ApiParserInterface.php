<?php

namespace phpdoc\parser;

use phpdoc\parser\docElement\AbstractDocElement;

/**
 * Interface ApiParserInterface
 */
interface ApiParserInterface
{
    /**
     * @param string $content
     * @param string $source
     * @return mixed
     */
    public function parse(string $content, string $source);

    /**
     * @return string
     */
    public function path(): string;

    /**
     * @return bool
     */
    public function isPreventGlobal(): bool;

    /**
     * @return string
     */
    public function getMethod(): string;

    /**
     * @return bool
     */
    public function isExtendRoot(): bool;

    /**
     * @return bool
     */
    public function isDeprecated(): bool;
}