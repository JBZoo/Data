<?php

/**
 * JBZoo Toolbox - Data.
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @see        https://github.com/JBZoo/Data
 */

declare(strict_types=1);

namespace JBZoo\Data;

use JBZoo\Utils\Filter;

use function JBZoo\Utils\bool;
use function JBZoo\Utils\float;
use function JBZoo\Utils\int;

/**
 * @psalm-suppress MissingTemplateParam
 */
class Data extends \ArrayObject
{
    public const LE = "\n";

    /**
     * @param null|array|false|string $data The data array
     */
    public function __construct($data = [])
    {
        $this->setFlags(\ArrayObject::ARRAY_AS_PROPS);

        if (\is_string($data) && $data !== '' && \file_exists($data)) {
            $data = self::readFile($data);
        }

        if (\is_string($data)) {
            $data = $this->decode($data);
        }

        parent::__construct(($data !== false && $data !== null) && \count($data) > 0 ? (array)$data : []);
    }

    /**
     * Magic method to convert the data to a string
     * Returns a serialized version of the data contained in
     * the data object using serialize().
     */
    public function __toString(): string
    {
        return $this->write();
    }

    /**
     * Checks if the given key is present.
     * @param string $name The key to check
     */
    public function has(string $name): bool
    {
        return $this->offsetExists($name);
    }

    /**
     * Get a value from the data given its key.
     * @param string     $key     The key used to fetch the data
     * @param null|mixed $default The default value
     * @param null|mixed $filter  Filter returned value
     */
    public function get(string $key, mixed $default = null, mixed $filter = null): mixed
    {
        self::checkDeprecatedFilter('get', $filter);

        $result = $default;
        if ($this->has($key)) {
            $result = $this->offsetGet($key);
        }

        return self::filter($result, $filter);
    }

    /**
     * Set a value in the data.
     * @param string $name  The key used to set the value
     * @param mixed  $value The value to set
     */
    public function set(string $name, mixed $value): static
    {
        $this->offsetSet($name, $value);

        return $this;
    }

    /**
     * Remove a value from the data.
     * @param string $name The key of the data to remove
     */
    public function remove(string $name): static
    {
        if ($this->has($name)) {
            $this->offsetUnset($name);
        }

        return $this;
    }

    /**
     * Encode an array or an object in INI format.
     */
    public function write(): string
    {
        return $this->encode($this->getArrayCopy());
    }

    /**
     * Find a key in the data recursively
     * This method finds the given key, searching also in any array or
     * object that's nested under the current data object.
     * Example: $data->find('parent-key.sub-key.sub-sub-key');.
     * @param string     $key       The key to search for. Can be composed using $separator as the key/su-bkey separator
     * @param null|mixed $default   The default value
     * @param null|mixed $filter    Filter returned value
     * @param string     $separator The separator to use when searching for sub keys. Default is '.'
     */
    public function find(string $key, mixed $default = null, mixed $filter = null, string $separator = '.'): mixed
    {
        self::checkDeprecatedFilter('find', $filter);

        $value = $this->get($key);

        // check if key exists in array
        if ($value !== null) {
            return self::filter($value, $filter);
        }

        // explode search key and init search data
        if ($separator === '') {
            throw new Exception("Separator can't be empty");
        }

        $parts = \explode($separator, $key);
        $data  = $this;

        foreach ($parts as $part) {
            // handle ArrayObject and Array
            if ($data instanceof \ArrayObject && $data[$part] !== null) {
                $data = $data[$part];
                continue;
            }

            if (\is_array($data) && isset($data[$part])) {
                $data = $data[$part];
                continue;
            }

            // Handle object
            if (\is_object($data) && \property_exists($data, $part)) {
                /** @phpstan-ignore-next-line */
                $data = $data->{$part};
                continue;
            }

            return self::filter($default, $filter);
        }

        // return existing value
        return self::filter($data, $filter);
    }

    /**
     * Find a value also in nested arrays/objects.
     * @param mixed $needle The value to search for
     */
    public function search(mixed $needle): float|bool|int|string|null
    {
        $aIterator = new \RecursiveArrayIterator($this->getArrayCopy());
        $iterator  = new \RecursiveIteratorIterator($aIterator);

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
     */
    public function flattenRecursive(): array
    {
        $flat = [];

        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($this->getArrayCopy())) as $value) {
            $flat[] = $value;
        }

        return $flat;
    }

    /**
     * @param  int|string $key
     * @return null|mixed
     * @phpstan-ignore-next-line
     */
    public function offsetGet($key): mixed
    {
        if (!\property_exists($this, (string)$key)) {
            return null;
        }

        return parent::offsetGet($key);
    }

    /**
     * Compare value by key with something.
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public function is(string $key, mixed $compareWith = true, bool $strictMode = false): bool
    {
        if (!\str_contains($key, '.')) {
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

    public function getInt(string $key, int $default = 0): int
    {
        return int($this->get($key, $default));
    }

    public function getFloat(string $key, float $default = 0.0): float
    {
        return float($this->get($key, $default));
    }

    public function getString(string $key, string $default = ''): string
    {
        return (string)$this->get($key, $default);
    }

    public function getArray(string $key, array $default = []): array
    {
        return (array)$this->get($key, $default);
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getBool(string $key, bool $default = false): bool
    {
        return bool($this->get($key, $default));
    }

    /**
     * @psalm-suppress UnsafeInstantiation
     */
    public function getSelf(string $key, array $default = []): self
    {
        if ($this->has($key) && $this->get($key) !== null) {
            // @phpstan-ignore-next-line
            return new static((array)$this->get($key, $default));
        }

        // @phpstan-ignore-next-line
        return new static($default);
    }

    public function findInt(string $key, int $default = 0): int
    {
        return int($this->find($key, $default));
    }

    public function findFloat(string $key, float $default = 0.0): float
    {
        return float($this->find($key, $default));
    }

    public function findString(string $key, string $default = ''): string
    {
        return (string)$this->find($key, $default);
    }

    public function findArray(string $key, array $default = []): array
    {
        return (array)$this->find($key, $default);
    }

    public function findBool(string $key, bool $default = false): bool
    {
        return bool($this->find($key, $default));
    }

    /**
     * @psalm-suppress UnsafeInstantiation
     */
    public function findSelf(string $key, array $default = []): self
    {
        if ($this->has($key) && $this->get($key) !== null) {
            // @phpstan-ignore-next-line
            return new static((array)$this->find($key, $default));
        }

        // @phpstan-ignore-next-line
        return new static($default);
    }

    /**
     * Utility Method to unserialize the given data.
     */
    protected function decode(string $string): mixed
    {
        /** @noinspection UnserializeExploitsInspection */
        return \unserialize($string, []);
    }

    /**
     * Utility Method to serialize the given data.
     * @param  string[] $data The data to serialize
     * @return string   The serialized data
     */
    protected function encode(array $data): string
    {
        return \serialize($data);
    }

    /**
     * Filter value before return.
     */
    protected static function filter(mixed $value, mixed $filter): mixed
    {
        if ($filter !== null) {
            $value = Filter::_($value, $filter);
        }

        return $value;
    }

    /**
     * @return false|string
     */
    protected static function readFile(string $filePath): bool|string
    {
        $contents = false;

        $realPath = \realpath($filePath);
        if ($realPath !== false) {
            $contents = \file_get_contents($realPath);
        }

        return $contents;
    }

    /**
     * Check is array is nested.
     */
    protected static function isMulti(array $array): bool
    {
        $arrayCount = \array_filter($array, '\is_array');

        return \count($arrayCount) > 0;
    }

    private static function checkDeprecatedFilter(string $prefix, mixed $filter): void
    {
        if (!\is_string($filter)) {
            return;
        }

        if (\in_array($filter, ['bool', 'int', 'float', 'string', 'array', 'arr'], true)) {
            if ($filter === 'arr') {
                $filter = 'array';
            }

            /** @psalm-suppress RedundantFunctionCall */
            $methodName = $prefix . \ucfirst(\strtolower($filter));
            @\trigger_error(
                "Instead of filter=\"{$filter}\", please use `\$data->{$methodName}(\$key, \$default)` method",
                \E_USER_DEPRECATED,
            );
        }
    }
}
