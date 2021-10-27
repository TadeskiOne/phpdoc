<?php

namespace phpdoc\parser\objectable\docElement;

/**
 * Class Api
 */
final class Api extends AbstractDocElement
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $title;

    /**
     * @return array
     */
    protected function getPropertiesTypes(): array
    {
        return  [
            'type' => 'string|null',
            'url' => 'string|null',
            'title' => 'string',
        ];
    }
}