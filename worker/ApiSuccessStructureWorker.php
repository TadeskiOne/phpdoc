<?php

namespace phpdoc\worker;

/**
 * Class ApiSuccessStructureWorker
 */
class ApiSuccessStructureWorker extends AbstractStructureWorker implements ApiWorkerInterface
{
    /**
     * @var array
     */
    protected $postProcessMessages = [
        'common' => [
            'element' => 'apiSuccessStructure',
            'usage' => '@apiSuccessStructure group',
            'example' => "@apiDefine MyValidSuccessStructureGroup Some title\n@apiSuccessStructure MyValidSuccessStructureGroup"
        ]
    ];
    /**
     * @var string
     */
    protected $processSource = 'defineSuccessStructure';
    /**
     * @var string
     */
    protected $postProcessTarget = 'successStructure';
}