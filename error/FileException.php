<?php

namespace restdoc\error;

use Throwable;

/**
 * Class FileException
 */
class FileException extends \Exception implements ErrorInterface
{
    /**
     * FileException constructor.
     * @param string $message
     * @param string $file
     * @param string $path
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = "",
        string $file = "",
        string $path = "",
        Throwable $previous = null
    ) {
        $message .= self::DELIMITER . "File: \t" . $file;
        $message .= self::DELIMITER . "Path: \t" . ($path ?: $file);
        $message = PHP_EOL . $message . PHP_EOL;

        parent::__construct($message, 1, $previous);
    }
}