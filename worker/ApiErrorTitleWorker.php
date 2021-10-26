<?php

namespace restdoc\worker;

use restdoc\PackageInfo;

/**
 * Class ApiErrorTitleWorker
 */
class ApiErrorTitleWorker extends AbstractTitleWorker implements ApiWorkerInterface
{
    /**
     * @var string[][]
     */
    protected $postProcessMessages = [
        'common' => [
            'element' => 'apiError',
            'usage' => '@apiError (group) varname',
            'example' => "@apiDefine MyValidErrorGroup Some title or 40X Error\n@apiError (MyValidErrorGroup) username"
        ]
    ];

    /**
     * @var string
     */
    protected $processSource = 'defineErrorTitle';

    /**
     * @var string
     */
    protected $postProcessTarget = 'error';
}
