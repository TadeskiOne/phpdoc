<?php

namespace phpdoc\parser\arrayble;

use phpdoc\parser\ApiParserInterface;
use phpdoc\Utils;

/**
 * Class ApiParamParser
 */
class ApiParamParser implements ApiParserInterface
{
    /**
     * @var string
     */
    private $group = '';

    /**
     * @var array
     *
     * Search: group, type, optional, fieldname, defaultValue, size, description
     * Example: {String{1..4}} [user.name='John Doe'] Users fullname.
     *
     * Naming convention:
     *     b -> begin
     *     e -> end
     *     name -> the field value
     *     oName -> wrapper for optional field
     *     wName -> wrapper for field
     */
    private $regExp = [
        'b' => '^',                                                 // start
        'oGroup' => [                                               // optional group: (404)
            'b' => '\\s*(?:\\(\\s*',                                // starting with '(', optional surrounding spaces
            'group' => '(.+?)',                                     // 1
            'e' => '\\s*\\)\\s*)?'                                  // ending with ')', optional surrounding spaces
        ],
        'oType' => [                                                // optional type: {string}
            'b' => '\\s*(?:\\{\\s*',                                // starting with '{', optional surrounding spaces
            'type' => '([a-zA-Z0-9\(\)#:\\.\\/\\\\\\[\\]_\|-]+)',   // 2
            'oSize' => [                                            // optional size within type: {string{1..4}}
                'b' => [
                    '\\s*(?:\\{\\s*',                               // starting with '{', optional surrounding spaces
                    'size' => '(.+?)',                              // 3
                    'e' => '\\s*\\}\\s*)?'                          // ending with '}', optional surrounding spaces
                ],
                'oAllowedValues' => [                               // optional allowed values within type: {string='abc','def'}
                    'b' => '\\s*(?:=\\s*',                          // starting with '=', optional surrounding spaces
                    'possibleValues' => '(.+?)',                    // 4
                    'e' => '(?=\\s*\\}\\s*))?'                      // ending with '}', optional surrounding spaces
                ],
                'e' => '\\s*\\}\\s*)?'                              // ending with '}', optional surrounding spaces
            ],
        ],
        'wName' => [
            'b' => '(\\[?\\s*',                                     // 5 optional optional-marker
            'name' => '([#@a-zA-Z0-9\\$\\:\\.\\/\\\\_-]+',          // 6
            'withArray' => '(?:\\[[a-zA-Z0-9\\.\\/\\\\_-]*\\])?)',  // https://github.com/apidoc/apidoc-core/pull/4
            'oDefaultValue' => [                                    // optional defaultValue
                'b' => '(?:\\s*=\\s*(?:',                           // starting with '=', optional surrounding spaces
                'withDoubleQuote' => '"([^"]*)"',                   // 7
                'withQuote' => '|\'([^\']*)\'',                     // 8
                'withoutQuote' => '|(.*?)(?:\\s|\\]|$)',            // 9
                'e' => '))?'
            ],
            'e' => '\\s*\\]?\\s*)'
        ],
        'description' => '(.*)?',                                   // 10
        'e' => '$|@'
    ];

    /**
     * @var string
     */
    private $allowedValuesWithDoubleQuoteRegExp = "/\"[^\"]*[^\"]\"/ui";

    /**
     * @var string
     */
    private $allowedValuesWithQuoteRegExp = "/'[^']*[^']'/ui";

    /**
     * @var string
     */
    private $allowedValuesRegExp = "/[^,\s]+/ui";

    /**
     * @return string
     */
    public function path(): string
    {
        return 'local.parameter.fields.' . $this->getGroup();
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @param string $content
     * @param string $source
     * @param string $defaultGroup
     * @return array|null
     */
    public function parse(string $content, string $source, string $defaultGroup = 'Parameter'): ?array
    {
        // replace Linebreak with Unicode
        $content = Utils::addLinebreaks(Utils::trim($content));

        preg_match('/'.$this->getParsedRegExp().'/iu', $content, $matches);

        if (!$matches) {
            return null;
        }

        // reverse Unicode Linebreaks
        $matches = array_map(
            function ($match) {
                return $match ? Utils::removeLinebreaks($match) : $match;
            },
            $matches
        );

        $allowedValues = $matches[4];

        if ($allowedValues) {
            switch (current(str_split($allowedValues,1))) {
                case '"':
                    $regExp = $this->allowedValuesWithDoubleQuoteRegExp;
                    break;
                case '\'':
                    $regExp = $this->allowedValuesWithQuoteRegExp;
                    break;
                default:
                    $regExp = $this->allowedValuesRegExp;
            }

            preg_match($regExp, $allowedValues, $allowedValuesMatch);

            $allowedValues = $allowedValuesMatch[0];
        }

        // Set global group variable
        $this->group = $matches[1] ?: $defaultGroup;

        return [
            'group' => $this->group,
            'type' => $matches[2],
            'size' => $matches[3],
            'allowedValues' => $allowedValues ?: [],
            'optional' => $matches[5] && $matches[5] === '[',
            'field' => $matches[6],
            'defaultValue' => $matches[7] ?? $matches[8] ?? $matches[9],
            'description' => Utils::unindent($matches[10] ?? ''),
        ];
    }

    /**
     * @return string
     */
    private function getParsedRegExp(): string
    {
        $callback = function (array $regExp, \Closure $callback): \Traversable {
            foreach ($regExp as $item) {
                if (is_string($item)) {
                    yield $item;
                } else {
                    foreach ($callback($item, $callback) as $subItem) {
                        yield $subItem;
                    }
                }
            }
        };

        $str = '';
        foreach ($callback($this->regExp, $callback) as $item) {
            $str .= $item;
        }

        return $str;
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
        return 'push';
    }

    /**
     * @return bool
     */
    public function isExtendRoot(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }
}
