<?php

namespace phpdoc\filter;

/**
 * Class ApiParamFilter
 */
class ApiParamFilter implements ApiFilterInterface
{
    /**
     * @inheritDoc
     */
    public function postFilter(array &$parsedFiles, array $filenames, string $tagName = 'parameter')
    {
        foreach ($parsedFiles as &$parsedFile) {
            foreach ($parsedFile as &$block) {
                if (isset($block['local'][$tagName]) && isset($block['local'][$tagName]['fields'])) {
                    $blockFields = $block['local'][$tagName]['fields'];
                    foreach (array_keys($blockFields) as $blockFieldKey) {
                        $fields = $block['local'][$tagName]['fields'][$blockFieldKey];
                        $newFields = [];
                        $existingKeys = [];

                        foreach ($fields as $field) {
                            if (!isset($existingKeys[$field['field']])) {
                                $existingKeys[$field['field']] = 1;
                                $newFields[] = $field;
                            }
                        }

                        $block['local'][$tagName]['fields'][$blockFieldKey] = $newFields;
                    }
                }
            }
        }
    }
}
