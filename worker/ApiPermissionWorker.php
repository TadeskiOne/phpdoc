<?php

namespace restdoc\worker;

use Composer\Semver\Comparator;
use restdoc\error\WorkerException;
use restdoc\PackageInfo;

/**
 * Class ApiPermissionWorker
 */
class ApiPermissionWorker implements ApiWorkerInterface
{
    /**
     * @var \string[][]
     */
    private $messages = [
        'common' => [
            'element' => 'apiPermission',
            'usage' => '@apiPermission group',
            'example' => "@apiDefine MyValidPermissionGroup Some title\n@apiPermission MyValidPermissionGroup"
        ]
    ];

    /**
     * @inheritDoc
     */
    public function preProcess(
        array &$parsedFiles,
        array $filenames,
        PackageInfo $packageInfos,
        string $target = 'definePermission'
    ): array {
       $source = 'define';
       $result = [
           $target => [],
       ];

        foreach ($parsedFiles as &$parsedFile) {
            foreach ($parsedFile as &$block) {
                if ($block['global'][$source]) {
                    $name = $block['global'][$source]['name'];
                    $version = $block['version'] ?? $packageInfos->defaultVersion;

                    if (!$result[$target][$name]) {
                        !$result[$target][$name] = [];
                    }

                    // fetch from local
                    $result[$target][$name][$version] = $block['global'][$source];
                }
            }
        }

        // remove empty target
        if ($result[$target] === []) {
            unset($result[$target]);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function postProcess(
        array $parsedFiles,
        array $filenames,
        array $preProcess,
        PackageInfo $packageInfos,
        string $source = 'defineGroup',
        string $target = 'group',
        array $messages = []
    ) {

        $messages = $messages ?: $this->messages;

        foreach ($parsedFiles as $parsedFileIndex => &$parsedFile) {
            foreach ($parsedFile as &$block) {
                if (!$block['local'][$target]) {
                    continue;
                }

                $newPermissions = [];
                foreach ($block['local'][$target] as $definition) {
                    $name = $definition['name'];
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
                        $matchedData['title'] = $definition['title'];
                        $matchedData['description'] = $definition['description'];
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

                    $newPermissions[] = $matchedData;
                }

                $block['local'][$target] = $newPermissions;
            }
        }
    }
}