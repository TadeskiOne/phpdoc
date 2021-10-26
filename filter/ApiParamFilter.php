<?php

namespace restdoc\filter;

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
                if ($block['local'][$tagName] && $block['local'][$tagName]['fields']) {
                    $blockFields = $block['local'][$tagName]['fields'];
                    foreach (array_keys($blockFields) as $blockFieldKey) {
                        $fields = $block['local'][$tagName]['fields'][$blockFieldKey];
                        $newFields = [];
                        $existingKeys = [];

                        foreach ($fields as $field) {
                            if (!$existingKeys[$field['field']]) {
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
