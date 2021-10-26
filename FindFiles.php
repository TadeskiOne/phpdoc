<?php

namespace restdoc;

use restdoc\error\FileException;
use Throwable;
use Traversable;

/**
 * Class FindFiles
 * @package restdoc
 */
class FindFiles {
    /**
     * @var string
     */
    private static $path;
    /**
     * @var array
     */
    private $excludeFilters = [];
    /**
     * @var array
     */
    private $includeFilters = [];

    /**
     * @param string $newPath
     */
    public function setPath(string $newPath): void
    {
        //print_r(realpath(trim($newPath, '/')));
        echo PHP_EOL,'++++++++++++++++++++++++++++++++',PHP_EOL;
        print_r(self::$path);
        echo PHP_EOL,'++++++++++++++++++++++++++++++++',PHP_EOL;
        self::$path = $newPath;
        print_r(self::$path);
        echo PHP_EOL,'++++++++++++++++++++++++++++++++',PHP_EOL;
        print_r($newPath);

        echo PHP_EOL,'++++++++++++++++++++++++++++++++',PHP_EOL,PHP_EOL;
    }

    /**
     * @param array $excludeFilters
     */
    public function setExcludeFilters(array $excludeFilters): void
    {
        if ($excludeFilters) {
            $this->excludeFilters = $excludeFilters;
        }
    }

    /**
     * @param array $includeFilters
     */
    public function setIncludeFilters(array $includeFilters): void
    {
        if ($includeFilters) {
            $this->includeFilters = $includeFilters;
        }
    }

    public function search() {
        $files = [];

        try {
            $files = array_map(
                function ($entry) {
                    return realpath($entry);
                },
                Sync::exec(self::$path)
            );

            print_r(Sync::exec(self::$path));
            print_r(self::$path);
            echo '===============================',PHP_EOL,PHP_EOL;


            // create RegExp Include Filter List
            $regExpIncludeFilters = [];
            $filters = (array)$this->includeFilters;

            foreach ($filters as $filter) {
                $regExpIncludeFilters[] = $filter;
            }

            // RegExp Include Filter
            $files = array_filter(
                $files,
                function ($filename) use ($regExpIncludeFilters) {
                    if (is_dir($filename)) {
                        return 0;
                    }

                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        $filename = str_replace('\\', '/', $filename);
                    }

                    foreach ($regExpIncludeFilters as $filter) {
                        if (preg_match($filter, $filename)) {
                            return 1;
                        }
                    }

                    return 0;
                }
            );

            // create RegExp Exclude Filter List
            $regExpExcludeFilters = [];
            $filters = (array)$this->excludeFilters;

            foreach ($filters as $filter) {
                $regExpExcludeFilters[] = $filter;
            }

            // RegExp Exclude Filter
            $files = array_filter(
                $files,
                function ($filename) use ($regExpExcludeFilters) {
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        $filename = str_replace('\\', '/', $filename);
                    }

                    foreach ($regExpExcludeFilters as $filter) {
                        if (preg_match($filter, $filename)) {
                            return 0;
                        }
                    }

                    return 1;
                }
            );
        } catch (Throwable $e) {
            throw $e;
        } finally {
            if (!$files || $files === []) {
                throw new FileException('No files found.',self::$path);
            }

            // remove source path prefix

            $files = array_map(
                function ($filename) {
                    return substr($filename, 0, strlen(self::$path)) === self::$path
                        ? substr($filename, strlen(self::$path))
                        : $filename;
                },
                $files
            );
        }

        return $files;
    }
}