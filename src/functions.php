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
 * @param array|null|string $data
 * @return Data
 */
function json($data = null)
{
    if ($data instanceof Data) {
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
 * @param array|null|string $data
 * @return Data
 */
function data($data = null)
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
 * @param array|null|string $data
 * @return Data
 */
function phpArray($data = null)
{
    if ($data instanceof Data) {
        return $data;
    }

    if (is_string($data)) {
        $result = new PHPArray($data);
    } else {
        $result = new PHPArray((array)$data);
    }

    return $result;
}

/**
 * @param array|null|string $data
 * @return Data
 */
function ini($data = null)
{
    if ($data instanceof Data) {
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
 * @param array|null|string $data
 * @return Data
 */
function yml($data = null)
{
    if ($data instanceof Data) {
        return $data;
    }

    if (is_string($data)) {
        $result = new Yml($data);
    } else {
        $result = new Yml((array)$data);
    }

    return $result;
}
