<?php

namespace restdoc\parser\objectable\docElement;

/**
 * Class ApiParam
 */
abstract class AbstractDocElement
{
    // Faster then using ReflectionApi
    abstract protected function getPropertiesTypes(): array;

    /**
     * @param string $fieldName
     * @param $value
     * @return $this
     */
    public function __call(string $fieldName, $value): self
    {
        $validate = function ($value) use ($fieldName) {
            $types = (array)explode('|', $this->getPropertiesTypes()[$fieldName]);

            foreach ($types as $type) {
                if (\Closure::fromCallable('is_'.$type)($value)) {
                    return true;
                }
            }

            return false;
        };


        if (isset($this->getPropertiesTypes()[$fieldName])) {
            if (!$validate($value[0])) {
                throw new \TypeError(
                    'Value "' . $value[0] . '" of ' . $fieldName . ' must type of "' .  $this->getPropertiesTypes()[$fieldName] . '"'
                );
            }

            $this->$fieldName = $value[0];

            return $this;
        }

        throw new \InvalidArgumentException('Undefined '.$fieldName.' field');
    }
}
