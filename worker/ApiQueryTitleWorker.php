<?php

namespace restdoc\worker;

/**
 * Class ApiQueryTitleWorker
 */
class ApiQueryTitleWorker extends AbstractTitleWorker implements ApiWorkerInterface
{
    /**
     * @var string[][]
     */
    protected $postProcessMessages = [
        'common' => [
            'element' => 'apiQuery',
            'usage' => '@apiQuery (group) varname',
            'example' => "@apiDefine MyValidParamGroup Some title\n@apiQuery (MyValidParamGroup) username"
        ]
    ];

    /**
     * @var string
     */
    protected $processSource = 'defineQueryTitle';

    /**
     * @var string
     */
    protected $postProcessTarget = 'query';
}