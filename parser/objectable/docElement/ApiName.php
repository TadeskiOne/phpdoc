<?php

namespace phpdoc\parser\objectable\docElement;

/**
 * Class ApiName
 */
final class ApiName extends AbstractDocElement
{
    /**
     * @var string
     */
    public $name = '';

    /**
     * @return string[]
     */
    protected function getPropertiesTypes(): array
    {
        return [
            'name' => 'string|null',
        ];
    }
}
