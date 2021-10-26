<?php

namespace phpdoc\worker;

/**
 * Class ApiHeaderStructureWorker
 */
class ApiHeaderStructureWorker extends AbstractStructureWorker implements ApiWorkerInterface
{
    /**
     * @var array
     */
    protected $postProcessMessages = [
        'common' => [
            'element' => 'apiHeaderStructure',
            'usage' => '@apiHeaderStructure group',
            'example' => "@apiDefine MyValidHeaderStructureGroup Some title\n@apiHeaderStructure MyValidHeaderStructureGroup"
        ]
    ];
    /**
     * @var string
     */
    protected $processSource = 'defineHeaderStructure';
    /**
     * @var string
     */
    protected $postProcessTarget = 'headerStructure';
}
