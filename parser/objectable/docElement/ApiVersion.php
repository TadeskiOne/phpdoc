<?php

namespace restdoc\parser\objectable\docElement;

/**
 * Class ApiVersion
 *
 * @method version(?string $version): self
 */
final class ApiVersion extends AbstractDocElement
{
    /**
     * @var string
     */
    public $version;

    /**
     * @return string[]
     */
    protected function getPropertiesTypes(): array
    {
        return [
            'version' => 'string|null',
        ];
    }
}