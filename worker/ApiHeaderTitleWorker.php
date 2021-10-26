<?php

namespace phpdoc\worker;

use restdoc\PackageInfo;

class ApiHeaderTitleWorker extends AbstractTitleWorker implements ApiWorkerInterface
{
    /**
     * @var string[][]
     */
    protected $postProcessMessages = [
        'common' => [
            'element' => 'apiHeader',
            'usage' => '@apiHeader (group) varname',
            'example' => "@apiDefine MyValidHeaderGroup Some title\n@apiHeader (MyValidHeaderGroup) Content-Type"
        ]
    ];

    /**
     * @var string
     */
    protected $processSource = 'defineHeaderTitle';

    /**
     * @var string
     */
    protected $postProcessTarget = 'header';
}