<?php

namespace restdoc\parser\objectable\docElement;

/**
 * Class ApiPrivate
 */
final class ApiPrivate extends AbstractDocElement
{
    /**
     * @var bool
     */
    public $private = false;

    /**
     * @var string[]
     */
    protected function getPropertiesTypes(): array
    {
        return [
            'private' => 'bool',
        ];
    }
}