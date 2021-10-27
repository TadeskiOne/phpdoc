<?php

namespace phpdoc\worker;

use phpdoc\error\WorkerException;
use phpdoc\PackageInfo;

/**
 * Class AbstractStructureWorker
 */
abstract class AbstractStructureWorker implements ApiWorkerInterface
{
    /**
     * @var array
     */
    protected $postProcessMessages = [];
    /**
     * @var string
     */
    protected $processSource = '';
    /**
     * @var string
     */
    protected $postProcessTarget = '';

    /**
     * @var ApiUseWorker
     */
    protected $worker;

    /**
     * AbstractStructureWorker constructor.
     * @param ApiUseWorker $worker
     */
    public function __construct(ApiUseWorker $worker)
    {
        $this->worker = $worker;
    }

    /**
     * @param array $parsedFiles
     * @param array $filenames
     * @param PackageInfo $packageInfos
     * @return array
     */
    public function preProcess(
        array $parsedFiles,
        array $filenames,
        PackageInfo $packageInfos
    ): array {
        return $this->worker->preProcess($parsedFiles, $filenames, $packageInfos, $this->processSource);
    }

    /**
     * @param array $parsedFiles
     * @param array $filenames
     * @param array $preProcess
     * @param PackageInfo $packageInfos
     * @throws WorkerException
     */
    public function postProcess(
        array &$parsedFiles,
        array $filenames,
        array $preProcess,
        PackageInfo $packageInfos
    ) {
        $this->worker->postProcess(
            $parsedFiles,
            $filenames,
            $preProcess,
            $packageInfos,
            $this->processSource,
            $this->postProcessTarget,
            $this->postProcessMessages
        );
    }
}