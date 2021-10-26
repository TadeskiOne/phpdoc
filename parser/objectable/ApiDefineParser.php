<?php

namespace restdoc\parser\objectable;

use restdoc\error\ParameterException;
use restdoc\parser\objectable\docElement\AbstractDocElement;
use restdoc\parser\objectable\docElement\ApiDefine;
use restdoc\Utils;

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
    public function parse(string $content, string $source, array $messages = []): ?AbstractDocElement
    {
        $messages = $messages ?: $this->messages;
        $content = Utils::trim($content);

        $parseRegExp = '/^(\w*)(.*?)(?:\s+|$)(.*)$/uim';
        preg_match_all($parseRegExp, $content, $matches);

        if ($matches === []) {
            return null;
        }

        if (!$matches[0] || $matches[0][0] === '') {
            throw new ParameterException(
                'No arguments found.',
                $messages['common']['element'],
                $messages['common']['usage'],
                $messages['common']['example']
            );
        }

        if (!$matches[2] || $matches[2][0] !== '') {
            throw new ParameterException(
                'Name must contain only alphanumeric characters.',
                $messages['common']['element'],
                $messages['common']['usage'],
                $messages['common']['example']
            );
        }

        $name = $matches[1][0];
        $title = $matches[3][0];

        preg_match_all($parseRegExp, $content, $matches);

        $description = implode('\n', $matches[0]);

        return (new ApiDefine())
            ->name($name)
            ->title($title)
            ->description(Utils::unindent($description));
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