<?php

namespace phpdoc;

use phpdoc\worker\ApiWorkerInterface;

class Worker
{
    /**
     * @var ApiWorkerInterface[]
     */
    private $workers = [];

    public function addWorker(string $workerName, ApiWorkerInterface $worker) {
        $this->workers[$workerName] = $worker;
    }

    /**
     * @param array $parsedFiles
     * @param array $parsedFilenames
     * @param PackageInfo $packageInfos
     */
    public function process(array &$parsedFiles, array $parsedFilenames, PackageInfo $packageInfos): void
    {
        // some smaller operation that are not outsourced to extra workers
        // TODO: add priority system first and outsource them then
        foreach ($parsedFiles as $fileIndex => &$parsedFile) {
            foreach ($parsedFile as &$block) {
                if ($block['global'] === [] && count($block['local']) > 0) {
                    if (!isset($block['local']['type'])) {
                        $block['local']['type'] = '';
                    }

                    if (!isset($block['local']['url'])) {
                        $block['local']['url'] = '';
                    }

                    if (!isset($block['local']['version'])) {
                        $block['local']['version'] = $packageInfos->defaultVersion;
                    }

                    if (!isset($block['local']['filename'])) {
                        $block['local']['filename'] = $parsedFilenames[$fileIndex];
                    }

                    // convert dir delimeter \\ to /
                    $block['local']['filename'] = str_replace('\\', '/', $block['local']['filename']);
                }
            }
        }

        // process transformations and assignments for each @api-Parameter
        $preProcessResults = [];

        foreach ($this->workers as $worker) {
            $preProcessResults = array_merge(
                $preProcessResults,
                $worker->preProcess($parsedFiles, $parsedFilenames, $packageInfos)
            );
        }

        foreach ($this->workers as $worker) {
            $worker->postProcess($parsedFiles, $parsedFilenames, $preProcessResults, $packageInfos);
        }
    }
}