<?php

namespace phpdoc\parser\objectable\docElement;

/**
 * Class ApiDescription
 *
 * @method description(?string $description): self
 */
final class ApiDescription extends AbstractDocElement
{
    /**
     * @var string
     */
    public $description = '';

    /**
     * @return string[]
     */
    protected function getPropertiesTypes(): array
    {
        return [
            'description' => 'string|null',
        ];
    }
}
