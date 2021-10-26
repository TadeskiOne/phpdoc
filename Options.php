<?php

namespace phpdoc;

class Options
{
    public $src = '';

    public $dest = '';

    public $excludeFilters = [];

    public $includeFilters = [];

    public $encoding = '';

    public $lineEnding = PHP_EOL;

    public $mode = 'amd';

    public $template;

    public $config;

    public $apiprivate = false;

    public $verbose;

    public $single;

    public $debug;

    public $parse = true;

    public $colorize = true;

    public $filters;

    public $languages;

    public $parsers;

    public $workers;

    public $silent = false;

    public $simulate;

    public $markdown;

    public $copyDefinitions;

    public $filterBy;
}