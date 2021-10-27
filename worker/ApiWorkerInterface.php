<?php

namespace phpdoc\worker;

use phpdoc\PackageInfo;

/**
 * Interface WorkerInterface
 */
interface ApiWorkerInterface
{
    /**
     * PreProcess
     *
     * @param Object[]    $parsedFiles
     * @param String[]    $filenames
     * @param PackageInfo $packageInfos
     * @returns array
     */
    public function preProcess(
        array $parsedFiles,
        array $filenames,
        PackageInfo $packageInfos
    ): array;

    /**
     * PostProcess
     *
     * @param Object[]    $parsedFiles
     * @param String[]    $filenames
     * @param Object[]    $preProcess
     * @param PackageInfo $packageInfos
     */
    public function postProcess(
        array &$parsedFiles,
        array $filenames,
        array $preProcess,
        PackageInfo $packageInfos
    );
}