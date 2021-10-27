<?php

namespace phpdoc;

/**
 * Class Filesystem
 */
class Filesystem
{
    /**
     * @param string $src
     * @param string $dst
     */
    public static function copyDirectory(string $src, string $dst):void
    {
        $dir = opendir($src);
        if (!file_exists($dst)) {
            mkdir($dst);
        }

        while(false !== ( $fileOrDir = readdir($dir)) ) {
            if (( $fileOrDir != '.' ) && ( $fileOrDir != '..' )) {
                if ( is_dir($src . '/' . $fileOrDir) ) {
                    self::copyDirectory($src . '/' . $fileOrDir, $dst . '/' . $fileOrDir);
                }
                else {
                    copy($src . '/' . $fileOrDir,$dst . '/' . $fileOrDir);
                }
            }
        }
        closedir($dir);
    }
}