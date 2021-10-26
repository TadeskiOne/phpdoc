<?php

namespace phpdoc\error;

use Throwable;

/**
 * Class ParserException
 */
class ParserException extends \Exception implements ErrorInterface
{
    /**
     * ParserException constructor.
     * @param string $message
     * @param string $file
     * @param string $block
     * @param string $element
     * @param string $source
     * @param array $extra
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = '',
        string $file = '',
        string $block = '',
        string $element = '',
        string $source = '',
        array $extra = [],
        Throwable $previous = null
    ) {
        $message .= self::DELIMITER . "File: \t" . $file;
        $message .= self::DELIMITER . "Block: \t" . $block;
        $message .= self::DELIMITER . "Element: \t@" . $element;
        $message .= self::DELIMITER . "Source: \t" . $source;
        $message .= self::DELIMITER . "Extra: \t" . implode(self::DELIMITER."\t",$extra);
        $message = PHP_EOL . $message . PHP_EOL;

        parent::__construct($message, 3, $previous);
    }
}
