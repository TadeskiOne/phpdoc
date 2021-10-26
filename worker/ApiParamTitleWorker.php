<?php

namespace restdoc\worker;

use Composer\Semver\Comparator;
use restdoc\error\WorkerException;
use restdoc\PackageInfo;

/**
 * Class ApiParamTitleWorker
 */
class ApiParamTitleWorker implements ApiWorkerInterface
{
    private $messages = [
        'common' => [
            'element' => 'apiParam',
            'usage' => '@apiParam (group) varname',
            'example' => "@apiDefine MyValidParamGroup Some title\n@apiParam (MyValidParamGroup) username"
        ]
    ];

    /**
     * @param array $parsedFiles
     * @param array $filenames
     * @param PackageInfo $packageInfos
     * @param String      $target       Target path in preProcess-Object (returned result), where the data should be set.
     * @return array|array[]
     */
    public function preProcess(
        array &$parsedFiles,
        array $filenames,
        PackageInfo $packageInfos,
        string $target = 'defineParamTitle'
    ): array {
        $source = 'define'; // relative path to the tree (global.), from where the data should be fetched.
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

                    $result[$target][$name][$version] = $block['global'][$source];
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
     * @param Object[]    $parsedFiles
     * @param String[]    $filenames
     * @param Object[]    $preProcess
     * @param PackageInfo $packageInfos
     * @param String      $source       Source path in preProcess-Object
     * @param String      $target       Relative path to the tree (local.), where the data should be modified.
     * @param string[][]      $messages
     */
    public function postProcess(
        array $parsedFiles,
        array $filenames,
        array $preProcess,
        PackageInfo $packageInfos,
        string $source = 'defineParamTitle',
        string $target = 'parameter',
        array $messages = []
    ) {
        $messages = $messages ?: $this->messages;

        foreach ($parsedFiles as $parsedFileIndex => &$parsedFile) {
            foreach ($parsedFile as &$block) {
                if (!$block['local'][$target] || !$block['local'][$target]['fields']) {
                    continue;
                }

                $newFields = [];
                foreach (array_keys($block['local'][$target]['fields']) as $fieldGroup) {
                    foreach ($block['local'][$target]['fields'][$fieldGroup] as $definition) {
                        $name = $definition['group'];
                        $version = $block['version'] ?? $packageInfos->defaultVersion;
                        $matchedData = [];

                        if (!$preProcess[$source] || !$preProcess[$source][$name]) {
                            // TODO: Enable in the next version
// At the moment the (groupname) is optional and must not be defined.
                            /*
                                                    var extra = [
                                                        { 'Groupname': name }
                                                    ];
                                                    throw new WorkerError('Referenced groupname does not exist / it is not defined with @apiDefine.',
                                                                          filenames[parsedFileIndex],
                                                                          block.index,
                                                                          messages.common.element,
                                                                          messages.common.usage,
                                                                          messages.common.example,
                                                                          extra);
                            */
// TODO: Remove in the next version
                            $matchedData['name'] = $name;
                            $matchedData['title'] = $name;
                        } elseif ($preProcess[$source][$name][$version]) {
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

                        if (!$newFields[$matchedData['title']]) {
                            $newFields[$matchedData['title']] = [];
                        }

                        $newFields[$matchedData['title']][] = $definition;
                    }
                }

                $block['local'][$target]['fields'] = $newFields;
            }
        }
    }
}