<?php

namespace phpdoc\error;

use Throwable;

/**
 * Class WorkerException
 */
class WorkerException extends \Exception implements ErrorInterface
{
    /**
     * WorkerException constructor.
     * @param string $message
     * @param string $file
     * @param string $block
     * @param string $element
     * @param string $definition
     * @param string $example
     * @param string $extra
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = '',
        string $file = '',
        string $block = '',
        string $element = '',
        string $definition = '',
        string $example = '',
        array $extra = [],
        Throwable $previous = null
    ) {
        $message .= self::DELIMITER . "File: \t" . $file;
        $message .= self::DELIMITER . "Block: \t" . $block;
        $message .= self::DELIMITER . "Element: \t@" . $element;
        $message .= self::DELIMITER . "Definition: \t" . $definition;
        $message .= self::DELIMITER . "Example: \t" . str_replace("\n", self::DELIMITER . "\t\t", $example);
        $message .= self::DELIMITER . "Extra: " . self::DELIMITER . "\t" . (
            $extra
            ? implode(
                self::DELIMITER . "\t",
                array_map(
                    function ($val) {
                        return str_replace("\n", '',key($val) . ': ' .  current($val));
                    },
                    $extra,
                )
            )
            : ''
        );
        $message = PHP_EOL . $message . PHP_EOL;

        parent::__construct($message, 0, $previous);
    }
}