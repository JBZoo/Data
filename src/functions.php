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
 * @return JSON
 */
function json($data = null)
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
 * @param array|null|string $data
 * @return Data
 * @codeCoverageIgnore
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
 * @return PHPArray
 * @codeCoverageIgnore
 */
function phpArray($data = null)
{
    if ($data instanceof PHPArray) {
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
 * @return Ini
 * @codeCoverageIgnore
 */
function ini($data = null)
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
 * @param array|null|string $data
 * @return Yml
 * @codeCoverageIgnore
 */
function yml($data = null)
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
