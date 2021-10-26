<?php

namespace restdoc\language;

/**
 * Class DefaultLanguage
 *
 * C#, Go, Dart, Java, JavaScript, PHP (all DocStyle capable languages)
 */
class DefaultLanguage extends AbstractLanguage
{
    // find document blocks between '#**' and '#*'
    public $docBlocksRegExp = '/\/\*\*\x{ffff}?(.+?)\x{ffff}?(?:\s*)?\*\//mui';

    // remove not needed ' * ' and tabs at the beginning
    public $inlineRegExp = '/^(\s*)?(\*)[ ]?/ium';
}