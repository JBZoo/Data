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
 * @param mixed $data
 * @return JSON
 */
function json($data = null): JSON
{
    if ($data instanceof JSON) {
        return $data;
    }

    if (is_string($data)) {
        $result = new JSON($data);
    } else {
        $result = new JSON((array)$data);
    }

    return $result;
}

/**
 * @param mixed $data
 * @return Data
 */
function data($data = null): Data
{
    if ($data instanceof Data) {
        return $data;
    }

    if (is_string($data)) {
        $result = new Data($data);
    } else {
        $result = new Data((array)$data);
    }

    return $result;
}

/**
 * @param mixed $data
 * @return PhpArray
 */
function phpArray($data = null): PhpArray
{
    if ($data instanceof PhpArray) {
        return $data;
    }

    if (is_string($data)) {
        $result = new PhpArray($data);
    } else {
        $result = new PhpArray((array)$data);
    }

    return $result;
}

/**
 * @param mixed $data
 * @return Ini
 */
function ini($data = null): Ini
{
    if ($data instanceof Ini) {
        return $data;
    }

    if (is_string($data)) {
        $result = new Ini($data);
    } else {
        $result = new Ini((array)$data);
    }

    return $result;
}

/**
 * @param mixed $data
 * @return Yml
 */
function yml($data = null): Yml
{
    if ($data instanceof Yml) {
        return $data;
    }

    if (is_string($data)) {
        $result = new Yml($data);
    } else {
        $result = new Yml((array)$data);
    }

    return $result;
}
