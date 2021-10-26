<?php

namespace restdoc;

use restdoc\error\FileException;

/**
 * Class App
 */
class App
{
    public const SPECIFICATION_VERSION = '0.3.0';

    public $settings;
    public $packageInfo;

    public function __construct(AppSettings $settings, PackageInfo $packageInfo)
    {
        $this->settings = $settings;
        $this->packageInfo = $packageInfo;
    }

    public function parse(Options $options)
    {
        try {
            $this->settings->options = $options;
            $parsedFiles = [];
            $parsedFilenames = [];
            // if input option for source is an array of folders,
            // parse each folder in the order provided.
            //app.log.verbose('run parser');
            if (is_array($options->src)) {
                foreach ($options->src as $folder) {
                    // Keep same options for each folder, but ensure the 'src' of options
                    // is the folder currently being processed.
                    $folderOptions = $options;
                    $folderOptions->src = trim($folder, '/') . '/';

                    $this->settings->parser->parseFiles($folderOptions, $parsedFiles, $parsedFilenames);
                }
            } else {
                // if the input option for source is a single folder, parse as usual.
                $options->src = trim($options->src, '/') . '/';

                $this->settings->parser->parseFiles($options, $parsedFiles, $parsedFilenames);
            }

            if ($parsedFiles === []) {
                return true;
            }

            // process transformations and assignments
            $this->settings->worker->process($parsedFiles, $parsedFilenames, $this->packageInfo);

            // cleanup
            $blocks = $this->settings->filter->process($parsedFiles, $parsedFilenames);

            // sort by group ASC, name ASC, version DESC
            uasort(
                $blocks,
                function ($a, $b) {
                    $nameA = $a['group'] . $a['name'];
                    $nameB = $b['group'] . $b['name'];

                    if ($nameA === $nameB) {
                        if ($a['version'] === $b['version']) {
                            return 0;
                        }

                        return $a['version'] > $b['version'] ? -1 : 1;
                    }

                    return ($nameA < $nameB) ? -1 : 1;
                }
            );

            // add apiDoc specification version
            $this->packageInfo->apidoc = self::SPECIFICATION_VERSION;

            // add apiDoc specification version
            //app.packageInfos.generator = app.generator;

            // api_data
            $apiData = json_encode($blocks);
            $apiData = preg_replace("/(\r\n|\n|\r)/ium", $this->settings->options->lineEnding, $apiData);

            // api_project
            $apiProject = json_encode($this->packageInfo);
            $apiProject = preg_replace("/(\r\n|\n|\r)/ium", $this->settings->options->lineEnding, $apiProject);

            return [
                'data' => $apiData,
                'project' => $apiProject,
            ];
        } catch (FileException $e) {
            echo $e->getMessage(). PHP_EOL;
            echo $e->getFile() . ' at line ' . $e->getLine() . PHP_EOL;

            return false;
        }
    }

    public function init()
    {
        //$app['filters'] =
    }
}