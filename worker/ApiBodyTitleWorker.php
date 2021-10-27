<?php

namespace phpdoc\worker;

use phpdoc\error\WorkerException;
use phpdoc\PackageInfo;

/**
 * Class ApiBodyTitleWorker
 */
class ApiBodyTitleWorker extends AbstractTitleWorker implements ApiWorkerInterface
{
    /**
     * @var string[][]
     */
    protected $postProcessMessages = [
        'common' => [
            'element' => 'apiBody',
            'usage' => '@apiBody (group) varname',
            'example' => "@apiDefine MyValidParamGroup Some title\n@apiBody (MyValidParamGroup) username"
        ]
    ];

    /**
     * @var string
     */
    protected $processSource = 'defineBodyTitle';

    /**
     * @var string
     */
    protected $postProcessTarget = 'body';
}