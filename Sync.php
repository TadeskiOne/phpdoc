<?php

namespace phpdoc;

/**
 * Class Sync
 *
 * @temporary
 */
class Sync
{
    /**
     * @param string $dir
     * @param array|int[] $opts
     * @param array $ls
     * @return array
     */
    public static function exec(string $dir, array $opts = ['depthLimit' => 100], array &$ls = []) {
        if (!$ls) {
            $dir = realpath($dir);
            $opts = $opts ?? [];
            $opts['fs'] = $opts['fs'] ?? [];

            if ($opts['depthLimit'] > -1) {
                $opts['rootDepth'] = count(str_split($dir)) + 1;
            }
        }

        $paths = array_map(
            function ($path) use ($dir) {
                return realpath($dir . '/' . $path);
            },
            array_filter(
                scandir(realpath($dir)),
                function ($path) {
                    return !in_array((string)$path, ['.', '..']);
                }
            )
        );



        foreach ($paths as $path) {
            $isUnderDepthLimit = count(explode('/', $path)) - $opts['rootDepth'] < $opts['depthLimit'];

            if (is_dir($path) && $isUnderDepthLimit) {
                self::exec($path, $opts, $ls);

            } else {
                $ls[] = $path;
            }
        }

        return $ls;
    }
}