<?php

namespace phpdoc\worker;

use phpdoc\PackageInfo;

/**
 * Class ApiStructureWorker
 */
class ApiStructureWorker extends AbstractStructureWorker implements ApiWorkerInterface
{
    /**
     * @var \string[][]
     */
    protected $postProcessMessages = [
        'common' => [
            'element' => 'apiStructure',
            'usage' => '@apiStructure group',
            'example' => "@apiDefine MyValidStructureGroup Some title\n@apiStructure MyValidStructureGroup"
        ]
    ];

    /**
     * @var string
     */
    protected $processSource = 'defineStructure';

    /**
     * @var string
     */
    protected $postProcessTarget = 'structure';
}