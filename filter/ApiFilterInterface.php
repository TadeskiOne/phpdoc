<?php

namespace restdoc\filter;

/**
 * Interface ApiFilterInterface
 * @package restdoc\filter
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