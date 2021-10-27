<?php

namespace phpdoc\worker;

use phpdoc\PackageInfo;

/**
 * Class ApiSampleRequestWorker
 */
class ApiSampleRequestWorker implements ApiWorkerInterface
{

    /**
     * @inheritDoc
     */
    public function preProcess(array &$parsedFiles, array $filenames, PackageInfo $packageInfos): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function postProcess(
        array $parsedFiles,
        array $filenames,
        array $preProcess,
        PackageInfo $packageInfos
    ) {
        $target = 'sampleRequest';

        foreach ($parsedFiles as &$parsedFile) {
            foreach ($parsedFile as &$block) {
                if (isset($block['local'][$target])) {
                    $newBlock = [];

                    foreach ($block['local'][$target] as $entry) {
                        if (isset($entry['url']) && $entry['url'] !== 'off') {
                            // Check if is an internal url
                            if (
                                $packageInfos->sampleUrl
                                && strlen($entry['url']) >= 4
                                && strtolower(substr($entry['url'], 0, 4)) !== 'http'
                            ) {
                                $entry['url'] = $packageInfos->sampleUrl . $entry['url'];
                            }

                            $newBlock[] = $entry;
                        }
                    }

                    if ($newBlock === []) {
                        unset($block['local'][$target]);
                    } else {
                        $block['local'][$target] = $newBlock;
                    }
                } elseif ($packageInfos->sampleUrl && $block['local'] && $block['local']['url']) {
                    if (
                        strlen($block['local']['url']) >= 4
                        && strtolower(substr($block['local']['url'], 0, 4)) !== 'http'
                    ) {
                        $url = $packageInfos->sampleUrl . $block['local']['url'];
                    } else {
                        $url = $block['local']['url'];
                    }

                    $block['local'][$target] = [['url' => $url]];
                }
            }
        }
    }
}