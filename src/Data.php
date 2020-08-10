<?php

/**
 * JBZoo Toolbox - Data
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Data
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/Data
 * @author     Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\Data;

use ArrayObject;
use JBZoo\Utils\Filter;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

use function JBZoo\Utils\bool;
use function JBZoo\Utils\float;
use function JBZoo\Utils\int;

/**
 * Class Data
 * @package JBZoo\Data
 */
class Data extends ArrayObject
{
    public const LE = "\n";

    /**
     * Class constructor
     * @param array|string|false $data The data array
     */
    public function __construct($data = [])
    {
        $this->setFlags(ArrayObject::ARRAY_AS_PROPS);

        if ($data && is_string($data) && file_exists($data)) {
            $data = self::readFile($data);
        }

        if (is_string($data)) {
            $data = $this->decode($data);
        }

        parent::__construct($data ? (array)$data : []);
    }

    /**
     * Utility Method to unserialize the given data
     * @param string $string
     * @return mixed
     */
    protected function decode(string $string)
    {
        /** @noinspection UnserializeExploitsInspection */
        return unserialize($string, []);
    }

    /**
     * Utility Method to serialize the given data
     * @param mixed $data The data to serialize
     * @return string The serialized data
     */
    protected function encode($data)
    {
        return serialize($data);
    }

    /**
     * Checks if the given key is present
     * @param string $name The key to check
     * @return boolean
     */
    public function has(string $name): bool
    {
        return $this->offsetExists($name);
    }

    /**
     * Get a value from the data given its key
     * @param string $key     The key used to fetch the data
     * @param mixed  $default The default value
     * @param mixed  $filter  Filter returned value
     * @return mixed
     */
    public function get(string $key, $default = null, $filter = null)
    {
        $result = $default;
        if ($this->has($key)) {
            $result = $this->offsetGet($key);
        }

        return self::filter($result, $filter);
    }

    /**
     * Set a value in the data
     * @param string $name  The key used to set the value
     * @param mixed  $value The value to set
     * @return $this
     */
    public function set(string $name, $value)
    {
        $this->offsetSet($name, $value);
        return $this;
    }

    /**
     * Remove a value from the data
     * @param string $name The key of the data to remove
     * @return $this
     */
    public function remove(string $name): self
    {
        if ($this->has($name)) {
            $this->offsetUnset($name);
        }

        return $this;
    }

    /**
     * Magic method to convert the data to a string
     * Returns a serialized version of the data contained in
     * the data object using serialize()
     * @return string
     */
    public function __toString()
    {
        return $this->write();
    }

    /**
     * Encode an array or an object in INI format
     * @return string
     */
    public function write(): string
    {
        return $this->encode($this->getArrayCopy());
    }

    /**
     * Find a key in the data recursively
     * This method finds the given key, searching also in any array or
     * object that's nested under the current data object.
     * Example: $data->find('parent-key.sub-key.sub-sub-key');
     *
     * @param string $key       The key to search for. Can be composed using $separator as the key/su-bkey separator
     * @param mixed  $default   The default value
     * @param mixed  $filter    Filter returned value
     * @param string $separator The separator to use when searching for sub keys. Default is '.'
     * @return mixed
     */
    public function find(string $key, $default = null, $filter = null, string $separator = '.')
    {
        $value = $this->get($key);

        // check if key exists in array
        if (null !== $value) {
            return self::filter($value, $filter);
        }

        // explode search key and init search data
        $parts = (array)explode($separator, $key);
        $data = $this;

        foreach ($parts as $part) {
            // handle ArrayObject and Array
            if (($data instanceof ArrayObject || is_array($data)) && isset($data[$part])) {
                $data = $data[$part];
                continue;
            }

            // handle object
            if (is_object($data) && isset($data->$part)) {
                $data = &$data->$part;
                continue;
            }

            return self::filter($default, $filter);
        }

        // return existing value
        return self::filter($data, $filter);
    }

    /**
     * Filter value before return
     *
     * @param mixed $value
     * @param mixed $filter
     * @return mixed
     */
    protected static function filter($value, $filter)
    {
        if (null !== $filter) {
            $value = Filter::_($value, $filter);
        }

        return $value;
    }

    /**
     * Find a value also in nested arrays/objects
     * @param mixed $needle The value to search for
     * @return string|float|int|bool|null
     */
    public function search($needle)
    {
        $aIterator = new RecursiveArrayIterator($this->getArrayCopy());
        $iterator = new RecursiveIteratorIterator($aIterator);

        while ($iterator->valid()) {
            $iterator->current();

            if ($iterator->current() === $needle) {
                return $aIterator->key();
            }

            $iterator->next();
        }

        return false;
    }

    /**
     * Return flattened array copy. Keys are <b>NOT</b> preserved.
     * @return array
     */
    public function flattenRecursive(): array
    {
        $flat = [];

        foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($this->getArrayCopy())) as $value) {
            $flat[] = $value;
        }

        return $flat;
    }

    /**
     * @param string $filePath
     * @return string|false
     */
    protected static function readFile(string $filePath)
    {
        $contents = false;

        if ($realPath = realpath($filePath)) {
            $contents = file_get_contents($realPath);
        }

        return $contents;
    }

    /**
     * Check is array is nested
     * @param array $array
     * @return bool
     */
    protected static function isMulti(array $array): bool
    {
        $arrayCount = array_filter($array, '\is_array');
        return count($arrayCount) > 0;
    }

    /**
     * @param mixed $index
     * @return mixed|null
     */
    public function offsetGet($index)
    {
        if (!property_exists($this, $index)) {
            return null;
        }

        return parent::offsetGet($index);
    }

    /**
     * Compare value by key with something
     *
     * @param string $key
     * @param mixed  $compareWith
     * @param bool   $strictMode
     * @return bool
     *
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public function is(string $key, $compareWith = true, bool $strictMode = false): bool
    {
        if (strpos($key, '.') === false) {
            $value = $this->get($key);
        } else {
            $value = $this->find($key);
        }

        if ($strictMode) {
            return $value === $compareWith;
        }

        /** @noinspection TypeUnsafeComparisonInspection */
        return $value == $compareWith;
    }

    /**
     * @param string $key
     * @param int    $default
     * @return int
     */
    public function getInt(string $key, int $default = 0): int
    {
        return int($this->get($key, $default));
    }

    /**
     * @param string $key
     * @param float  $default
     * @return float
     */
    public function getFloat(string $key, float $default = 0.0): float
    {
        return float($this->get($key, $default));
    }

    /**
     * @param string $key
     * @param string $default
     * @return string
     */
    public function getString(string $key, string $default = ''): string
    {
        return (string)$this->get($key, $default);
    }

    /**
     * @param string $key
     * @param array  $default
     * @return array
     */
    public function getArray(string $key, array $default = []): array
    {
        return (array)$this->get($key, $default);
    }

    /**
     * @param string $key
     * @param bool   $default
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getBool(string $key, bool $default = false): bool
    {
        return bool($this->get($key, $default));
    }

    /**
     * @param string $key
     * @param array  $default
     * @return static
     * @psalm-suppress UnsafeInstantiation
     */
    public function getSelf(string $key, array $default = []): self
    {
        if ($this->has($key) && null !== $this->get($key)) {
            // @phpstan-ignore-next-line
            return new static((array)$this->get($key, $default));
        }

        // @phpstan-ignore-next-line
        return new static($default);
    }

    /**
     * @param string $key
     * @param int    $default
     * @return int
     */
    public function findInt(string $key, int $default = 0): int
    {
        return int($this->find($key, $default));
    }

    /**
     * @param string $key
     * @param float  $default
     * @return float
     */
    public function findFloat(string $key, float $default = 0.0): float
    {
        return float($this->find($key, $default));
    }

    /**
     * @param string $key
     * @param string $default
     * @return string
     */
    public function findString(string $key, string $default = ''): string
    {
        return (string)$this->find($key, $default);
    }

    /**
     * @param string $key
     * @param array  $default
     * @return array
     */
    public function findArray(string $key, array $default = []): array
    {
        return (array)$this->find($key, $default);
    }

    /**
     * @param string $key
     * @param bool   $default
     * @return bool
     */
    public function findBool(string $key, bool $default = false): bool
    {
        return bool($this->find($key, $default));
    }

    /**
     * @param string $key
     * @param array  $default
     * @return static
     * @psalm-suppress UnsafeInstantiation
     */
    public function findSelf(string $key, array $default = []): self
    {
        if ($this->has($key) && null !== $this->get($key)) {
            // @phpstan-ignore-next-line
            return new static((array)$this->find($key, $default));
        }

        // @phpstan-ignore-next-line
        return new static($default);
    }
}
