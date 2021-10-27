<?php

namespace phpdoc\parser\objectable\docElement;

/**
 * Class ApiDefine
 *
 * @method name(string $name): self
 * @method title(string $title): self
 * @method description(string $type): self
 */
final class ApiDefine extends AbstractDocElement
{
    /**
     * @var string
     */
    public $name = '';

    /**
     * @var string
     */
    public $title = '';

    /**
     * @var string
     */
    public $description = '';

    /**
     * @return array
     */
    protected function getPropertiesTypes(): array
    {
        return  [
            'name' => 'string|null',
            'url' => 'string|null',
            'description' => 'string|null',
        ];
    }
}
