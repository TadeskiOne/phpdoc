<?php

namespace phpdoc\worker;

/**
 * Class ApiSuccessTitleWorker
 */
class ApiSuccessTitleWorker extends AbstractTitleWorker implements ApiWorkerInterface
{
    /**
     * @var string[][]
     */
    protected $postProcessMessages = [
        'common' => [
            'element' => 'apiSuccess',
            'usage' => '@apiSuccess (group) varname',
            'example' => "@apiDefine MyValidParamGroup Some title or 200 OK\n@apiSuccess (MyValidParamGroup) username"
        ]
    ];

    /**
     * @var string
     */
    protected $processSource = 'defineSuccessTitle';

    /**
     * @var string
     */
    protected $postProcessTarget = 'success';
}
