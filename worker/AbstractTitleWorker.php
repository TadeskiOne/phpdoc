<?php

namespace restdoc\worker;

use restdoc\error\WorkerException;
use restdoc\PackageInfo;

/**
 * Class AbstractTitleWorker
 */
abstract class AbstractTitleWorker implements ApiWorkerInterface
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
     * @var ApiParamTitleWorker
     */
    protected $worker;

    /**
     * ApiBodyTitleWorker constructor.
     * @param ApiParamTitleWorker $worker
     */
    public function __construct(ApiParamTitleWorker $worker)
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
        array &$parsedFiles,
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
        array $parsedFiles,
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