<?php

namespace phpdoc\parser\objectable\docElement;

/**
 * Class ApiGroup
 */
final class ApiGroup extends AbstractDocElement
{
    /**
     * @var string
     */
    public $group = '';

    /**
     * @return string[]
     */
    protected function getPropertiesTypes(): array
    {
        return [
            'group' => 'string|null',
        ];
    }
}
