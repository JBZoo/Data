<?php
/**
 * JBZoo Data
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   Data
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/Data
 * @author    Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\Data;

/**
 * Class Data
 * @package JBZoo\Data
 */
class Data extends \ArrayObject
{
    const LE = "\n";

    /**
     * Class constructor
     * @param array|string $data The data array
     */
    public function __construct($data = array())
    {
        if ($data && is_string($data) && file_exists($data)) {
            $data = $this->_readFile($data);
        }

        if (is_string($data)) {
            $data = $this->_decode($data);
        }

        parent::__construct($data ? (array)$data : array());
    }

    /**
     * Utility Method to unserialize the given data
     * @param string $string
     * @return mixed
     */
    protected function _decode($string)
    {
        return unserialize($string);
    }

    /**
     * Utility Method to serialize the given data
     * @param mixed $data The data to serialize
     * @return string The serialized data
     */
    protected function _encode($data)
    {
        return serialize($data);
    }

    /**
     * Checks if the given key is present
     * @param string $name The key to check
     * @return boolean
     */
    public function has($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * Get a value from the data given its key
     * @param string $key     The key used to fetch the data
     * @param mixed  $default The default value
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->offsetGet($key);
        }

        return $default;
    }

    /**
     * Set a value in the data
     * @param string $name  The key used to set the value
     * @param mixed  $value The value to set
     * @return $this
     */
    public function set($name, $value)
    {
        $this->offsetSet($name, $value);
        return $this;
    }

    /**
     * Remove a value from the data
     * @param string $name The key of the data to remove
     * @return $this
     */
    public function remove($name)
    {
        $this->offsetUnset($name);
        return $this;
    }

    /**
     * Magic method to allow for correct isset() calls
     * @param string $name The key to search for
     * @return boolean
     */
    public function __isset($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * Magic method to get values as object properties
     * @param string $name The key of the data to fetch
     * @return mixed
     */
    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * Magic method to set values through object properties
     * @param string $name  The key of the data to set
     * @param mixed  $value The value to set
     */
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     * Magic method to unset values using unset()
     * @param string $name The key of the data to set
     */
    public function __unset($name)
    {
        $this->offsetUnset($name);
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
    public function write()
    {
        return $this->_encode($this->getArrayCopy());
    }

    /**
     * Find a key in the data recursively
     * This method finds the given key, searching also in any array or
     * object that's nested under the current data object.
     * Example: $data->find('parentkey.subkey.subsubkey');
     *
     * @param string $key       The key to search for. Can be composed using $separator as the key/subkey separator
     * @param mixed  $default   The default value
     * @param string $separator The separator to use when searching for subkeys. Default is '.'
     * @return mixed
     */
    public function find($key, $default = null, $separator = '.')
    {
        $value = $this->get($key, $default);

        // check if key exists in array
        if (null !== $value) {
            return $value;
        }

        // explode search key and init search data
        $parts = explode($separator, $key);
        $data  = $this;

        foreach ($parts as $part) {
            // handle ArrayObject and Array
            if (($data instanceof \ArrayObject || is_array($data)) && array_key_exists($data[$part])) {
                $data = &$data[$part];
                continue;
            }

            // handle object
            if (is_object($data) && isset($data->$part)) {
                $data = &$data->$part;
                continue;
            }

            return $default;
        }

        // return existing value
        return $data;
    }

    /**
     * Find a value also in nested arrays/objects
     * @param mixed $needle The value to search for
     * @return string|false
     */
    public function search($needle)
    {
        $aIterator = new \RecursiveArrayIterator($this);
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
     * @return array
     */
    public function flattenRecursive()
    {
        $flat = array();

        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($this)) as $value) {
            $flat[] = $value;
        }

        return $flat;
    }

    /**
     * @param string $filePath
     * @return null|string
     */
    protected function _readFile($filePath)
    {
        $contents = null;

        if ($realPath = realpath($filePath)) {
            $handle   = fopen($realPath, "rb");
            $contents = fread($handle, filesize($realPath));
            fclose($handle);
        }

        return $contents;
    }

    /**
     * Check is array is nested
     * @param $array
     * @return bool
     */
    protected function _isMulti($array)
    {
        $arrayCount = array_filter($array, 'is_array');
        if (count($arrayCount) > 0) {
            return true;
        }

        return false;
    }
}
