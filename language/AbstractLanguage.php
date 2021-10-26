<?php

namespace restdoc\language;

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
