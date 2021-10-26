<?php

namespace restdoc\worker;

use Composer\Semver\Comparator;
use restdoc\error\WorkerException;
use restdoc\PackageInfo;

/**
 * Class ApiParamTitleWorker
 */
class ApiUseWorker implements ApiWorkerInterface
{
    private $messages = [
        'common' => [
            'element' => 'apiUse',
            'usage' => '@apiUse group',
            'example' => "@apiDefine MyValidGroup Some title\n@apiUse MyValidGroup"
        ]
    ];

    /**
     * @param array $parsedFiles
     * @param array $filenames
     * @param PackageInfo $packageInfos
     * @param String $target Target path in preProcess-Object (returned result), where the data should be set.
     * @return array|array[]
     */
    public function preProcess(
        array &$parsedFiles,
        array $filenames,
        PackageInfo $packageInfos,
        string $target = 'define'
    ): array {
        $source = $target; // relative path to the tree (global.), from where the data should be fetched.
        $result = [
            $target => [],
        ];

        foreach ($parsedFiles as &$parsedFile) {
            foreach ($parsedFile as &$block) {
                if ($block['global'][$source]) {
                    $name = $block['global'][$source]['name'];
                    $version = $block['version'] ?? $packageInfos->defaultVersion;

                    if (!$result[$target][$name]) {
                        $result[$target][$name] = [];
                    }

                    // fetch from local
                    $result[$target][$name][$version] = $block['local'];
                }
            }
        }

        if ($result[$target] === []) {
            unset($result[$target]);
        }

        return $result;
    }

    /**
     * PostProcess
     *
     * @param Object[] $parsedFiles
     * @param String[] $filenames
     * @param Object[] $preProcess
     * @param PackageInfo $packageInfos
     * @param String $source Source path in preProcess-Object
     * @param String $target Relative path to the tree (local.), where the data should be modified.
     * @param string[][] $messages
     */
    public function postProcess(
        array $parsedFiles,
        array $filenames,
        array $preProcess,
        PackageInfo $packageInfos,
        string $source = 'define',
        string $target = 'use',
        array $messages = []
    ) {
        $messages = $messages ?: $this->messages;

        foreach ($parsedFiles as $parsedFileIndex => &$parsedFile) {
            foreach ($parsedFile as &$block) {
                $loopCounter = 0;
                while ($block['local'][$target]) {
                    if ($loopCounter > 10) {
                        throw new WorkerException(
                            'recursion depth exceeds limit with @apiUse',
                            $filenames[$parsedFileIndex],
                            $block['index'],
                            $messages['common']['element'],
                            $messages['common']['usage'],
                            $messages['common']['example'],
                            [['Groupname' => $block['name']]]
                        );
                    }

                    //create a copy of the elements for save iterating of the elements
                    $blockClone = array_slice($block['local'][$target], 0);

                    // remove unneeded target before starting the loop, to allow a save insertion of new elements
                    // TODO: create a cleanup filter
                    unset($block['local'][$target]);

                    foreach ($blockClone as $blockIndex => $definition) {
                        $name = $definition['name'];
                        $version = $block['version'] ?? $packageInfos->defaultVersion;

                        if (!$preProcess[$source] || !$preProcess[$source][$name]) {
                            throw new WorkerException(
                                'Referenced groupname does not exist / it is not defined with @apiDefine.',
                                $filenames[$parsedFileIndex],
                                $block['index'],
                                $messages['common']['element'],
                                $messages['common']['usage'],
                                $messages['common']['example'],
                                [['Groupname' => $block['name']]]
                            );
                        }

                        $matchedData = [];
                        if ($preProcess[$source][$name][$version]) {
                            $matchedData = $preProcess[$source][$name][$version];
                        } else {
                            $foundIndex = -1;
                            $lastVersion = $packageInfos->defaultVersion;
                            $versionKeys = array_keys($preProcess[$source][$name]);

                            foreach ($versionKeys as $versionIndex => $currentVersion) {
                                if (
                                    Comparator::greaterThanOrEqualTo($version, $currentVersion)
                                    && Comparator::greaterThanOrEqualTo($currentVersion, $lastVersion)
                                ) {
                                    $lastVersion = $currentVersion;
                                    $foundIndex = $versionIndex;
                                }
                            }

                            if ($foundIndex === -1) {
                                throw new WorkerException(
                                    'Referenced definition has no matching or a higher version. '
                                    . 'Check version number in referenced define block.',
                                    $filenames[$parsedFileIndex],
                                    $block['index'],
                                    $messages['common']['element'],
                                    $messages['common']['usage'],
                                    $messages['common']['example'],
                                    [
                                        ['Groupname' => $name],
                                        ['Version' => $version],
                                        ['Defined versions' => $versionKeys],
                                    ]
                                );
                            }

                            $versionName = $versionKeys[$foundIndex];
                            $matchedData = $preProcess[$source][$name][$versionName];
                        }
                        // copy matched elements into parsed block
                        $block['local'] = array_merge_recursive($block['local'], $matchedData);
                    }
                }
            }
        }
    }
}