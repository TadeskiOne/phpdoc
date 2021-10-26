<?php

namespace restdoc\parser\objectable\docElement;

/**
 * Class ApiParam
 *
 * @method group(string $group): self
 * @method type(string $type): self
 * @method size(string $type): self
 * @method allowedValues(array $type): self
 * @method optional(bool $type): self
 * @method field(string $type): self
 * @method defaultValue(string $type): self
 * @method description(string $type): self
 */
final class ApiParam extends AbstractDocElement
{
    /**
     * @var string
     */
    public $group = '';

    /**
     * @var string
     */
    public $type = '';

    /**
     * @var string
     */
    public $size = '';

    /**
     * @var array
     */
    public $allowedValues = [];

    /**
     * @var bool
     */
    public $optional = false;

    /**
     * @var string
     */
    public $field = '';

    /**
     * @var string|mixed
     */
    public $defaultValue = '';

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
            'group' => 'string|null',
            'type' => 'string|null',
            'size' => 'string|null',
            'allowedValues' => 'array|null',
            'optional' => 'bool',
            'field' => 'string|null',
            'defaultValue' => 'string|null',
            'description' => 'string|null',
        ];
    }
}
