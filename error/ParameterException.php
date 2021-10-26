<?php

namespace restdoc\error;

use Throwable;

/**
 * Class ParameterException
 */
class ParameterException extends \Exception implements ErrorInterface
{
    /**
     * @var string
     */
    public $element = '';
    /**
     * @var string
     */
    public $definition = '';
    /**
     * @var string
     */
    public $example = '';

    /**
     * ParameterException constructor.
     * @param string $message
     * @param string $element
     * @param string $definition
     * @param string $example
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = '',
        string $element = '',
        string $definition = '',
        string $example = '',
        Throwable $previous = null
    ) {
        $this->element = $element;
        $this->definition = $definition;
        $this->example = $example;

        parent::__construct($message, 2, $previous);
    }

    /**
     * @return string
     */
    public function asString(): string
    {
        $message = $this->message;
        $message .= self::DELIMITER . "Element: \t@" . $this->element;
        $message .= self::DELIMITER . "Definition: \t" . $this->definition;
        $message .= self::DELIMITER . "Example: \t" . $this->example;
        $message = PHP_EOL . $message . PHP_EOL;

        return $message;
    }
}