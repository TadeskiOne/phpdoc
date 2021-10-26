<?php

namespace phpdoc;

use InvalidArgumentException;

/**
 * Class AppSettings
 *
 * @property Parser $parser
 * @property Worker $worker
 * @property Filter $filter
 * @property Options $options
 */
class AppSettings
{
    /**
     * @var Options
     */
    private $properties = [];

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        static $types = [
            'parser' => Parser::class,
            'worker' => Worker::class,
            'filter' => Filter::class,
            'options' => Options::class,
        ];

        if (isset($types[$name])) {
            if ($value instanceof $types[$name]) {
                $this->properties[$name] = $value;
            } else {
                throw new InvalidArgumentException(
                    'Value for property "' . $name . '" must be compatible with "' . $types[$name] . '"'
                );
            }
        } else {
            throw new InvalidArgumentException('Undefined property "' . $name . '"');
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }

        throw new InvalidArgumentException('Undefined property "' . $name . '"');
    }
}