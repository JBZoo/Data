<?php
/**
 * Data
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   Data
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @link      http://github.com/SmetDenis/Data
 */

namespace SmetDenis\Data;

use ArrayObject,
    RecursiveArrayIterator,
    RecursiveIteratorIterator;

/**
 * Class Data
 * @package SmetDenis\Data
 */
class Data extends ArrayObject
{
    /**
     * Class constructor
     * @param array $data The data array
     */
    public function __construct($data = array())
    {
        parent::__construct($data ? (array)$data : array());
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
        if ($this->offsetExists($key)) {
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
        return !$this ? '' : $this->write($this->getArrayCopy());
    }

    /**
     * Utility Method to serialize the given data
     * @param mixed $data The data to serialize
     * @return string The serialized data
     */
    protected function write($data)
    {
        return serialize($data);
    }

    /**
     * Find a key in the data recursively
     * This method finds the given key, searching also in any array or
     * object that's nested under the current data object.
     *
     * Example:
     * $data->find('parentkey.subkey.subsubkey');
     *
     * @param string $key       The key to search for. Can be composed using $separator as the key/subkey separator
     * @param mixed  $default   The default value
     * @param string $separator The separator to use when searching for subkeys. Default is '.'
     *
     * @return mixed
     */
    public function find($key, $default = null, $separator = '.')
    {
        $key   = (string)$key;
        $value = $this->get($key);
        // check if key exists in array
        if ($value !== null) {
            return $value;
        }
        // explode search key and init search data
        $parts = explode($separator, $key);
        $data  = $this;

        foreach ($parts as $part) {

            // handle ArrayObject and Array
            if (($data instanceof ArrayObject || is_array($data)) && isset($data[$part])) {
                if ($data[$part] === null) {
                    return $default;
                }
                $data =& $data[$part];
                continue;
            }

            // handle object
            if (is_object($data) && isset($data->$part)) {
                if ($data->$part === null) {
                    return $default;
                }
                $data =& $data->$part;
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
     * @return string
     */
    public function searchRecursive($needle)
    {
        $aIt = new RecursiveArrayIterator($this);
        $it  = new RecursiveIteratorIterator($aIt);

        while ($it->valid()) {
            if ($it->current() == $needle) {
                return $aIt->key();
            }
            $it->next();
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
        foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($this)) as $value) {
            $flat[] = $value;
        }
        return $flat;
    }
}
