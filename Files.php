<?php

namespace phpdoc;

use Exception;
use Iterator;
use Traversable;

/**
 * Class Files
 *
 * @experemental
 */
class Files implements Iterator
{
    public const DEFAULT_DEPTH_LIMIT = 100;

    public $depthLimit = self::DEFAULT_DEPTH_LIMIT;
    /**
     * @var bool
     */
    public $useRealPath = true;

    /**
     * @var string
     */
    private $dir = '';

    /**
     * @var string
     */
    private $realPathDir = '';

    /**
     * @var array
     */
    private $files = [];

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var Parser
     */
    public $parser;

    /**
     * Files constructor.
     * @param string $dir
     * @param int $depthLimit
     * @throws Exception
     */
    public function __construct(string $dir, int $depthLimit = self::DEFAULT_DEPTH_LIMIT)
    {
        $this->dir = trim($dir, '/');
        $this->realPathDir = realpath($this->dir);
        $this->files = iterator_to_array($this->scanDir($this->realPathDir));
        if (!$this->files || count($this->files) === 0) {
            throw new Exception('No files found in directory '. $dir);
        }
        $this->position = 0;
        $this->depthLimit = $depthLimit;
        $this->parser = new Parser();
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return string
     */
    public function current(): string
    {
        $current = $this->files[$this->position];
        /*$current = $this->useRealPath
            ? $current
            : str_replace($this->realPathDir, $this->dir, $current);*/

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $current = str_replace('\\', '/', $current);
        }

        return $current;
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }


    public function next(): void
    {
        ++$this->position;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->files[$this->position]);
    }

    /**
     * @param string $dir
     * @return Traversable`
     */
    private function scanDir(string $dir): Traversable
    {
        $this->depthLimit = 100;
        $rootDepth = count(explode('/', $dir)) + 1;
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
            $isUnderDepthLimit = count(explode('/', $path)) - $rootDepth < $this->depthLimit;
            if (is_dir($path) && $isUnderDepthLimit) {
                foreach ($this->scanDir($path) as $subPath) {
                    yield $subPath;
                }
            } else {
                yield $path;
            }
        }
    }
}
