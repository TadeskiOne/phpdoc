<?php

namespace restdoc\parser\objectable\docElement;

/**
 * Class ApiDeprecated
 *
 * @method deprecated($deprecated): self
 */
final class ApiDeprecated extends AbstractDocElement
{
    /**
     * @var array|bool
     */
    public $deprecated = false;

    /**
     * @return array
     */
    protected function getPropertiesTypes(): array
    {
        return  [
            'deprecated' => 'bool|array',
        ];
    }
}
