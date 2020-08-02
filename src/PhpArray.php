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

/**
 * Class PhpArray
 * @package JBZoo\Data
 */
class PhpArray extends Data
{
    /**
     * Class constructor
     *
     * @param array|string $data The data array
     */
    public function __construct($data = [])
    {
        if ($data && is_string($data) && file_exists($data)) {
            $data = $this->decode($data);
        }

        parent::__construct($data ? (array)$data : []);
    }

    /**
     * Utility Method to unserialize the given data
     *
     * @param string $string
     * @return mixed|null
     */
    protected function decode(string $string)
    {
        if (file_exists($string)) {
            return include $string;
        }
    }

    /**
     * Utility Method to serialize the given data
     *
     * @param mixed $data The data to serialize
     * @return string The serialized data
     */
    protected function encode($data)
    {
        $data = [
            '<?php',
            '',
            'return ' . var_export($data, true) . ';',
        ];

        return implode(self::LE, $data);
    }
}
