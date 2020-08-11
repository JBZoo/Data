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
 * Class JSON
 *
 * @package JBZoo\Data
 */
class JSON extends Data
{
    /**
     * Utility Method to unserialize the given data
     *
     * @param string $string
     * @return mixed
     */
    protected function decode(string $string)
    {
        return json_decode($string, true, 512, JSON_BIGINT_AS_STRING);
    }

    /**
     * Does the real json encoding adding human readability. Supports automatic indenting with tabs
     *
     * @param mixed $data
     * @return string
     */
    protected function encode($data): string
    {
        return (string)json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING);
    }
}
