<?php

namespace phpdoc\worker;

/**
 * Class ApiErrorStructureWorker
 */
class ApiErrorStructureWorker extends AbstractStructureWorker implements ApiWorkerInterface
{
    /**
     * @var array
     */
    protected $postProcessMessages = [
        'common' => [
            'element' => 'apiErrorStructure',
            'usage' => '@apiErrorStructure group',
            'example' => "@apiDefine MyValidErrorStructureGroup Some title\n@apiErrorStructure MyValidErrorStructureGroup"
        ]
    ];
    /**
     * @var string
     */
    protected $processSource = 'defineErrorStructure';
    /**
     * @var string
     */
    protected $postProcessTarget = 'errorStructure';
}
