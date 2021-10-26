<?php

namespace phpdoc\language;

/**
 * Class AbstractLanguage
 */
abstract class AbstractLanguage
{
    /**
     * @var string
     */
    public $docBlocksRegExp = '';

    /**
     * @var string
     */
    public $inlineRegExp = '';
}
