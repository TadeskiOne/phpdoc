<?php

namespace phpdoc\parser\arrayble;

use phpdoc\error\ParameterException;
use phpdoc\parser\ApiParserInterface;
use phpdoc\Utils;

/**
 * Class ApiDefineParser
 */
class ApiDefineParser extends AbstractParamParser implements ApiParserInterface
{
    /**
     * @var string[][]
     */
    public $messages = [
        'common' => [
            'element' => 'apiDefine',
            'usage' => '@apiDefine name',
            'example' => '@apiDefine MyValidName',
        ]
    ];

    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source, array $messages = []): ?array
    {
        $messages = $messages ?: $this->messages;
        $content = Utils::trim($content);

        $parseRegExp = '/^(\w*)(.*?)(?:\s+|$)(.*)$/uim';
        preg_match($parseRegExp, $content, $matches);

        if ($matches === []) {
            return null;
        }

        if ($matches[0] === '') {
            throw new ParameterException(
                'No arguments found.',
                $messages['common']['element'],
                $messages['common']['usage'],
                $messages['common']['example']
            );
        }

        if ($matches[2] !== '') {
            print_r($matches);
            throw new ParameterException(
                'Name must contain only alphanumeric characters.',
                $messages['common']['element'],
                $messages['common']['usage'],
                $messages['common']['example']
            );
        }

        $name = $matches[1];
        $title = $matches[3];

        preg_match($parseRegExp, $content, $matches);

        $description = implode("\n", $matches);

        return [
            'name' => trim($name, " \n"),
            'title' => trim($title, " \n"),
            'description' => Utils::unindent($description),
        ];
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return 'global.define';
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return 'insert';
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }
}