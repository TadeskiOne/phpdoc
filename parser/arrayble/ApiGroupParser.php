<?php

namespace phpdoc\parser\arrayble;


use phpdoc\parser\ApiParserInterface;
use phpdoc\Utils;

/**
 * Class ApiGroupParser
 */
class ApiGroupParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?array
    {
        $group = Utils::trim($content);

        if ($group === '') {
            return null;
        }

        return ['group' => preg_replace('/(\s+)/iu','_', $group)];
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return 'local';
    }

    /**
     * @return bool
     */
    public function isPreventGlobal(): bool
    {
        return false;
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
    public function isExtendRoot(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }
}