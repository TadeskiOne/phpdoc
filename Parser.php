<?php

namespace phpdoc;

use phpdoc\error\ParameterException;
use phpdoc\error\ParserException;
use phpdoc\language\AbstractLanguage;
use phpdoc\parser\ApiParserInterface;

/**
 * Class Parser
 * @property  $filename
 * @property  $src
 */
class Parser
{
    /**
     * @var FindFiles
     */
    private $findFiles;

    /**
     * Parser constructor.
     * @param FindFiles $findFiles
     */
    public function __construct(FindFiles $findFiles)
    {
        $this->findFiles = $findFiles;
    }

    /**
     * @var AbstractLanguage[]
     */
    private $languages = [];

    /**
     * @var ApiParserInterface[]
     */
    private $parsers = [];

    private $parsedFileElements = [];
    private $parsedFiles = [];
    private $countDeprecated = [];
    private $filterTag = null;

    /**
     * @param string $languageName
     * @param AbstractLanguage $language
     */
    public function addLanguage(string $languageName, AbstractLanguage $language): void
    {
        $this->languages[$languageName] = $language;
    }

    /**
     * @param string $parserName
     * @param ApiParserInterface $parser
     */
    public function addParser(string $parserName, ApiParserInterface $parser): void
    {
        $this->parsers[$parserName] = $parser;
    }

    public function parseFiles(Options $options, array &$parsedFiles = [], array &$parsedFilenames = [])
    {
        echo PHP_EOL, $options->src, PHP_EOL, PHP_EOL;
       // $ffin = clone $this->findFiles;
        $this->findFiles->setExcludeFilters($options->excludeFilters);
        $this->findFiles->setIncludeFilters($options->includeFilters);
        $this->findFiles->setPath($options->src);
        //$ffin->setPath($options);


        $files = $this->findFiles->search();

        //print_r($files);
        // Parser
        foreach ($files as $filename) {
            //$filename = $options->src . $file;
            $parsedFile = $this->parseFile($filename, $options->encoding);

            if ($parsedFile) {
                $parsedFiles[] = $parsedFile;
                $parsedFilenames[] = $filename;
            }
        }
    }

    /**
     * @param string $filename
     * @param string $encoding
     * @return array|void
     */
    public function parseFile(string $filename, string $encoding = 'utf8')
    {
        $this->filename = $filename;
        $this->extension = strtolower(Utils::extname($filename));
        // TODO: Not sure if this is correct. Without skipDecodeWarning we got string errors
        // https://github.com/apidoc/apidoc-core/pull/25
        $fileContent = file_get_contents($filename);

        return $this->parseSource($fileContent, $encoding, $filename);
    }

    public function parseSource(string $fileContent, string $encoding, string $filename)
    {
        $this->src = mb_convert_encoding($fileContent, mb_detect_encoding($fileContent), $encoding);

        // unify line-breaks
        $this->src = preg_replace('/\r\n/iu', "\n", $this->src);

        $this->blocks = [];
        $this->indexApiBlocks = [];

        // determine blocks
        $this->blocks = $this->findBlocks();
        if ($this->blocks === []) {
            return [];
        }

        // determine elements in blocks
        $this->elements = array_map(
            function ($block) use ($filename) {
                return $this->findElements($block, $filename);
            },
            $this->blocks
        );

        //($this->elements);

        if ($this->elements === []) {
            return [];
        }

        // determine list of blocks with API elements
        $this->indexApiBlocks = $this->findBlockWithApiGetIndex($this->elements);
        if ($this->indexApiBlocks === []) {
            return [];
        }

        return $this->parseBlockElements($this->indexApiBlocks, $this->elements, $filename);
    }

    private function parseBlockElements($indexApiBlocks, $detectedElements, $filename)
    {
        $parsedBlocks = [];

        foreach ($indexApiBlocks as $blockIndex) {
            $elements = $detectedElements[$blockIndex];
            $blockData = [
                'global' => [],
                'local' => []
            ];
            $countAllowedMultiple = 0;

            /** @var Element $element */
            //print_r($elements);
            foreach ($elements as $element) {
                /** @var ApiParserInterface $elementParser */
                $elementParser = $this->parsers[$element->name] ?? null;

                if (!$elementParser) {
                    //TODO add warning
                    print_r($element);
                } else {
                    if ($elementParser->isDeprecated()) {
                        $this->countDeprecated[$element->sourceName] = $this->countDeprecated[$element->sourceName]
                            ? $this->countDeprecated[$element->sourceName] + 1
                            : 1;

                        $message = '@' . $element->sourceName . ' is deprecated';
                        if ($elementParser['alternative']) {
                            $message = '@' . $element->sourceName . ' is deprecated, please use ' . $elementParser['alternative'];
                        }

                        if ($this->countDeprecated[$element->sourceName] === 1) {
                            //TODO add warn($message)
                        } else {
                            //TODO add verbose($message)
                        }
                    }


                    try {
                        // parse element and retrieve values
                        $values = $elementParser->parse($element->content, $element->source);

                        // HINT: pathTo MUST be read after elementParser.parse, because of dynamic paths
                        // Add all other options after parse too, in case of a custom plugin need to modify params.

                        // check if it is allowed to add to global namespace
                        $preventGlobal = $elementParser->isPreventGlobal();

                        // allow multiple inserts into pathTo
                        $allowMultiple = true;

                        // path to an array, where the values should be attached
                        $pathTo = '';
                        if ($elementParser->path()) {
                            $pathTo = $elementParser->path();
                        }

                        if (!$pathTo) {
                            throw new ParserException(
                                'pathTo is not defined in the parser file.',
                                '',
                                '',
                                $element->sourceName
                            );
                        }

                        // method how the values should be attached (insert or push)
                        $attachMethod = $elementParser->getMethod() ?: 'push';

                        if ($attachMethod !== 'insert' && $attachMethod !== 'push') {
                            throw new ParserException(
                                'Only push or insert are allowed parser method values.',
                                '',
                                '',
                                $element->sourceName
                            );
                        }

                        // TODO: put this into "converters"
                        if ($values) {
                            /*echo PHP_EOL , '===============================';
                            echo PHP_EOL , '$values', PHP_EOL;
                            echo PHP_EOL , $element->content, PHP_EOL;
                            print_r($values);
                            echo PHP_EOL , '===============================', PHP_EOL;*/

                            // Markdown.
                        } else {
                            throw new ParserException(
                                'Empty parser result.',
                                $this->filename,
                                ($blockIndex + 1),
                                $element->sourceName,
                                $element->source
                            );
                        }
                    } catch (ParameterException $e) {
                        $extra = [];

                        if ($e->definition) {
                            $extra[] = ['Definition' => $e->definition];
                        }
                        if ($e->example) {
                            $extra[] = ['Example' => $e->example];
                        }
                       // print_r($e);
                        throw new ParserException(
                            $e->getMessage(),
                            $this->filename,
                            $blockIndex + 1,
                            $element->sourceName,
                            $element->source
                        );
                    } catch (\Throwable | \Error $e) {
                        throw new ParserException(
                            'Undefined error.',
                            $filename,
                            ($blockIndex + 1),
                            $element->sourceName,
                            $element->source,
                            [],
                            $e
                        );
                    }

                    if (!$values) {
                        throw new ParserException(
                            'Empty parser result.',
                            $this->filename,
                            ($blockIndex + 1),
                            $element->sourceName,
                            $element->source
                        );
                    }

                    if ($preventGlobal) {
                        // Check if count global namespace entries > count allowed
                        // (e.g. @successTitle is global, but should co-exist with @apiErrorStructure)
                        if (count($blockData['global']) > $countAllowedMultiple) {
                            throw new ParserException(
                                'Only one definition or usage is allowed in the same block.',
                                $this->filename,
                                ($blockIndex + 1),
                                $element->sourceName,
                                $element->source
                            );
                        }
                    }

                    // only one global allowed per block
                    if ($pathTo === 'global' || substr($pathTo, 0, 7) === 'global.') {
                        if ($allowMultiple) {
                            $countAllowedMultiple += 1;
                        } else {
                            if (count($blockData['global']) > 0) {
                                throw new ParserException(
                                    'Only one definition is allowed in the same block.',
                                    $this->filename,
                                    ($blockIndex + 1),
                                    $element->sourceName,
                                    $element->source
                                );
                            }

                            if ($preventGlobal === true) {
                                throw new ParserException(
                                    'Only one definition or usage is allowed in the same block.',
                                    $this->filename,
                                    ($blockIndex + 1),
                                    $element->sourceName,
                                    $element->source
                                );
                            }
                        }
                    }

                    //print_r($blockData);
                    if (!ArrayHelper::getValue($blockData, $pathTo)) {
                        ArrayHelper::setValue($blockData, $pathTo, []);
                        //$this->createObjectPath($blockData, $pathTo, $attachMethod);
                    }

                    /*echo $pathTo.PHP_EOL;
                    $blockDataPath = $this->pathToObject($blockData, $pathTo);
                    print_r($blockDataPath);
                    echo PHP_EOL;
                    print_r($attachMethod);
                    echo PHP_EOL;*/

                    // insert Fieldvalues in Path-Array
                    if ($attachMethod === 'push') {
                        ArrayHelper::pushValue($blockData, $pathTo, $values);
                    } else {
                        ArrayHelper::mergeValue($blockData, $pathTo, $values);
                    }

                    //ArrayHelper::setValue($blockData, $pathTo, $values);
                    // insert Fieldvalues in Mainpath
                    if ($elementParser->isExtendRoot()) {
                        $blockData = array_merge($blockData, (array)$values);
                    }

                    $blockData['index'] = $blockIndex + 1;
                }
            }
            if ($blockData['index'] && $blockData['index'] > 0) {
                $parsedBlocks[] = $blockData;
            }
        }

        return $parsedBlocks;
    }


    /**
     * @param array $src
     * @param string $path
     * @param string $attachMethod
     * @return array
     */
    public function createObjectPath(array $src, string $path = '', string $attachMethod = '')
    {
        if (!$path) {
            return $src;
        }

        $pathParts = explode('.', $path);
        $current = $src;

        foreach ($pathParts as $part) {
            if (!isset($current[$part])) {
                $current[$part] = [];
            }

            $current = $current[$part];
        }

        return $current;
    }

    /**
     * @param array $src
     * @param string $path
     * @return array|mixed
     */
    public function pathToObject(array $src, string $path = '')
    {
        if (!$path) {
            return $src;
        }

        $pathParts = explode('.', $path);
        $current = $src;

        foreach ($pathParts as $part) {
            $current = $current[$part];
        }

        return $current;
    }

    /**
     * @return array|array[]|null[]|string[]|\string[][]
     */
    private function findBlocks(): array
    {
        $src = $this->src;

        // Replace Linebreak with Unicode
        $src = Utils::addLinebreaks(Utils::unindent($src));

        $regexForFile = $this->languages[$this->extension] ?? $this->languages['default'];

        preg_match_all($regexForFile->docBlocksRegExp, $src, $matches);

        return array_map(
            function ($block) use ($regexForFile){
                return preg_replace(['/\x{ffff}/uim', $regexForFile->inlineRegExp], ["\n", ''], $block);
            },
            $matches[2] ?? $matches[1]
        );
    }

    private function findBlockWithApiGetIndex(array $blocks) {
        $foundIndexes = [];

        // get value to filter by
        $valueToFilter = ($this->filterTag) ? /*app.options.filterBy.split('=')[1]*/ : null;

        foreach ($blocks as $i => $blockElements) {
            $found = false;
            $isToFilterBy = false;
            $isDefine = false;

            /** @var Element $element */
            foreach ($blockElements as $element) {
                // check apiIgnore
                if (substr($element->name,0, 9) === 'apiignore') {
                    //app.log.debug('apiIgnore found in block: ' + i);
                    $found = false;
                    break;
                }

                // check app.options.apiprivate and apiPrivate
                if (/*!app.options.apiprivate &&*/ substr($element->name,0, 10) === 'apiprivate') {
                    //app.log.debug('private flag is set to false and apiPrivate found in block: ' + i);
                    $found = false;
                    break;
                }

                // check if the user want to filter by some specific tag
                if ($this->filterTag) {
                    // we need to add all apidefine
                    if (substr($element->name,0, 9) === 'apidefine') {
                        $isDefine = true;
                    }
                    if (substr($element->name,0, strlen($this->filterTag)) === $this->filterTag && $element->content === $valueToFilter) {
                        $isToFilterBy = true;
                    }
                }

                if (substr($element->name, 0, 3) === 'api')
                    $found = true;
            }

            // add block if it's apidefine or the tag is equal to the value defined in options
            if ($this->filterTag) {
                $found = $found && ($isToFilterBy || $isDefine);
            }

            if ($found) {
                $foundIndexes[] = $i;
                //app.log.debug('api found in block: ' + i);
            }
        }

        return $foundIndexes;
    }

    /**
     * @param array $block
     * @param string $filename
     * @return array
     */
    private function findElements(string $block, string $filename): array
    {
        $elements = [];

        // Replace Linebreak with Unicode
        $block = Utils::addLinebreaks($block);

        // Elements start with @
        preg_match_all('/(@(\w*)\s?(.*?)(?=\x{ffff}[\s\*]*@|$))/umi', $block, $matches);

        array_map(
            function (string $source, string $sourceName, string $content) {
                return [
                    'source' => $source,
                    'name' => strtolower($sourceName),
                    'sourceName' => $sourceName,
                    'content' => $content,
                ];
            },
            $matches[1],
            $matches[2],
            $matches[3],
        );

        return array_map(
            function (string $source, string $sourceName, string $content) {
                $element = new Element();
                // reverse Unicode Linebreaks
                $element->source = Utils::removeLinebreaks($source);
                $element->name = strtolower($sourceName);
                $element->sourceName = $sourceName;
                $element->content = Utils::removeLinebreaks($content);
                //app.hook('parser-find-element-' + element.name, element, block, filename);
                return $element;
            },
            $matches[1],
            $matches[2],
            $matches[3],
        );
    }
}
