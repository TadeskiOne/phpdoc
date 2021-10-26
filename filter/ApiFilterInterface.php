<?php

namespace phpdoc\filter;

/**
 * Interface ApiFilterInterface
 * @package phpdoc\filter
 */
interface ApiFilterInterface
{
    /**
     * Post Filter parsed results.
     *
     * @param Object[] $parsedFiles
     * @param String[] $filenames
     */
    public function postFilter(array &$parsedFiles, array $filenames);
}