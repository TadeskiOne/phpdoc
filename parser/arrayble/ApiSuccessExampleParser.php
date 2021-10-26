<?php

namespace phpdoc\parser\arrayble;

use phpdoc\parser\ApiParserInterface;
use phpdoc\Utils;

/**
 * Class ApiSuccessExampleParser
 */
class ApiSuccessExampleParser extends AbstractExampleParser implements ApiParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $content, string $source): ?array
    {
        $url = Utils::trim($content);

        if ($url === '') {
            return null;
        }

        return ['url' => $url];
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return 'local.sampleRequest';
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }
}