<?php

namespace phpdoc\parser\objectable\docElement;

/**
 * Class ApiSuccessExample
 */
final class ApiSuccessExample extends AbstractDocElement
{
    /**
     * @var string
     */
    public $url = '';

    /**
     * @return string[]
     */
    protected function getPropertiesTypes(): array
    {
        return [
            'url' => 'string|null'
        ];
    }
}