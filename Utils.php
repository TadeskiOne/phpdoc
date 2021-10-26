<?php

namespace restdoc;

/**
 * Class Utils
 */
class Utils {
    /**
     * @param string $str
     * @return string
     */
    public static function addLinebreaks(string $str): string
    {
        return preg_replace('/\n/uim', html_entity_decode('&#xFFFF;', ENT_NOQUOTES, 'UTF-8'), $str);
    }

    /**
     * @param string $str
     * @return string
     */
    public static function removeLinebreaks(string $str): string
    {
        return preg_replace('/\x{FFFF}/uim', "\n", $str);
    }

    /**
     * @param string $str
     * @return string
     */
    public static function unindent(string $str): string
    {
        $lines = explode("\n", $str);

        $xs = array_filter(
            $lines,
            function ($x) {
                return preg_match('/\S/i', $x);
            }
        );
        asort($xs);

        if ($xs === []) {
            return $str;
        }

        $a = str_split(current($xs));
        $b = str_split($xs[array_key_last($xs)]);

        $maxLength = min(count($a), count($b));

        $i = 0;
        while ($i < $maxLength && preg_match('/\s/i', $a[$i]) && $a[$i] === $b[$i]) {
            $i++;
        }

        if ($i === 0) {
            return $str;
        }

        return implode(
            "\n",
            array_map(
                function ($line) use ($i) {
                    return substr($line, $i);
                },
                $lines
            )
        );
    }

    /**
     * @param string $str
     * @return string
     */
    public static function trim(string $str): string
    {
        //return preg_replace('/^\s*|\s*$/ui', '', $str);
        return trim($str, "\s* ");
    }

    /**
     * @param string $path
     * @return string
     */
    public static function extname(string $path): string
    {
        preg_match('/^(\/?|)([\s\S]*?)((?:\.{1,2}|[^\/]+?|)(\.[^.\/]*|))(?:[\/]*)$/iu', $path, $splitPath);

        return substr($splitPath[4] ?? '', 1);
    }
}
