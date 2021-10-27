<?php

namespace phpdoc\parser\objectable\docElement;

/**
 * Class ApiExample
 *
 * @method title(?string $title): self
 * @method type(?string $type): self
 * @method content(?string $content): self
 */
final class ApiExample extends AbstractDocElement
{
    /**
     * @var string
     */
    public $title = '';

    /**
     * @var string
     */
    public $type = '';

    /**
     * @var string
     */
    public $content = '';

    /**
     * @return string[]
     */
    protected function getPropertiesTypes(): array
    {
        return [
            'title' => 'string|null',
            'type' => 'string|null',
            'content' => 'string|null',
        ];
    }
}
