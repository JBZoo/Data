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

use Symfony\Component\Yaml\Yaml;

/**
 * Class Yml
 *
 * @package JBZoo\Data
 */
class Yml extends Data
{
    /**
     * Utility Method to serialize the given data
     *
     * @param mixed $data The data to serialize
     * @return string The serialized data
     */
    protected function encode($data)
    {
        return Yaml::dump($data, 10, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK | Yaml::DUMP_NULL_AS_TILDE);
    }

    /**
     * Utility Method to unserialize the given data
     *
     * @param string $string
     * @return mixed
     */
    protected function decode(string $string)
    {
        return Yaml::parse($string);
    }
}
