<?php

namespace restdoc\filter;

/**
 * Class ApiErrorFilter
 */
class ApiErrorFilter implements ApiFilterInterface
{
    /**
     * @var ApiParamFilter
     */
    private $filter;

    /**
     * ApiErrorFilter constructor.
     * @param ApiParamFilter $filter
     */
    public function __construct(ApiParamFilter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param Object[] $parsedFiles
     * @param String[] $filenames
     */
    public function postFilter(array &$parsedFiles, array $filenames)
    {
        $this->filter->postFilter($parsedFiles, $filenames, 'error');
    }
}