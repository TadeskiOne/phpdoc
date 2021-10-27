<?php

namespace phpdoc\parser\arrayble;

use Composer\Semver\Semver;
use phpdoc\error\ParameterException;
use phpdoc\parser\ApiParserInterface;
use phpdoc\Utils;
use UnexpectedValueException;

/**
 * Class ApiVersionParser
 */
class ApiVersionParser implements ApiParserInterface
{
    /**
     * @param string $content
     * @param string $source
     * @return array|null
     */
    public function parse(string $content, string $source): ?array
    {
        $version = trim(Utils::trim($content), " \n");

        if ($version === '') {
            return null;
        }

        try {
            if (!Semver::satisfies($version, 'x.x.x')) {
                echo $version, PHP_EOL;
                throw new ParameterException(
                    'Version format not valid.',
                    'apiVersion',
                    '@apiVersion major.minor.patch',
                    '@apiVersion 1.2.3'
                );
            }
        }catch (UnexpectedValueException $e) {
            echo $version, PHP_EOL;
            throw new ParameterException(
                'Version format not valid.',
                'apiVersion',
                '@apiVersion major.minor.patch',
                '@apiVersion 1.2.3'
            );
        }

        return ['version' => $version];
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