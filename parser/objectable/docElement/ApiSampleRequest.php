<?php

namespace restdoc\parser\objectable\docElement;

/**
 * Class ApiSampleRequest
 *
 * @method url(string $url): self
 */
final class ApiSampleRequest extends AbstractDocElement
{
    /**
     * @var
     */
    public $url = '';

    /**
     * @return string[]
     */
    protected function getPropertiesTypes(): array
    {
        return [
            'url' => 'string|url'
        ];
    }
}