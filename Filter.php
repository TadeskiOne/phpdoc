<?php

namespace phpdoc;

use phpdoc\filter\ApiFilterInterface;

/**
 * Class Filter
 */
class Filter
{
    /**
     * @var ApiFilterInterface[]
     *
     * [(string) Filter name => (ApiFilterInterface) Filter]
     */
    private $filters = [];

    /**
     * @param string $name
     * @param ApiFilterInterface $filter
     */
    public function addFilter(string $name, ApiFilterInterface $filter): void
    {
        $this->filters[$name] = $filter;
    }

    /**
     * @param array $parsedFiles
     * @param array $parsedFilenames
     * @return array
     */
    public function process(array $parsedFiles, array $parsedFilenames): array
    {
        // filter each @api-Parameter
        foreach ($this->filters as $filter) {
            $filter->postFilter($parsedFiles, $parsedFilenames);
        }

        // reduce to local blocks where global is empty
        $blocks = [];

        foreach ($parsedFiles as $parsedFile) {
            foreach ($parsedFile as $block) {
                if ($block['global'] === [] && count($block['local']) > 0) {
                    $blocks[] = $block['local'];
                }
            }
        }

        return $blocks;
    }
}