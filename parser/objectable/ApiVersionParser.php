<?php

namespace phpdoc\parser\objectable;

use phpdoc\parser\objectable\docElement\AbstractDocElement;
use phpdoc\parser\objectable\docElement\ApiVersion;
use phpdoc\Utils;

/**
 * Class ApiVersionParser
 */
class ApiVersionParser implements ApiParserInterface
{
    /**
     * @param string $content
     * @param string $source
     * @return AbstractDocElement|null
     */
    public function parse(string $content, string $source): ?AbstractDocElement
    {
        $version = Utils::trim($content);

        if ($version === '') {
            return null;
        }

        /**
         * TODO add SemVer validation
         *
         * if ( ! semver.valid(content))
        throw new ParameterError('Version format not valid.',
        'apiVersion', '@apiVersion major.minor.patch', '@apiVersion 1.2.3');

         */

        return (new ApiVersion())
            ->version($version);
    }

    /**
     * @return string
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
        return true;
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }
}