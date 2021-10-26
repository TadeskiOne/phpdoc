<?php

namespace phpdoc\parser\objectable\docElement;

/**
 * Class ApiUse
 */
final class ApiUse extends AbstractDocElement
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