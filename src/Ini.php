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

final class Ini extends AbstractData
{
    protected function decode(string $string): mixed
    {
        return \parse_ini_string($string, true, \INI_SCANNER_NORMAL);
    }

    protected function encode(array $data): string
    {
        return $this->render($data);
    }

    protected function render(array $data = [], array $parent = []): string
    {
        $result = [];

        foreach ($data as $dataKey => $dataValue) {
            if (\is_array($dataValue)) {
                if (self::isMulti($dataValue)) {
                    $sections = \array_merge($parent, (array)$dataKey);
                    $result[] = '';
                    $result[] = '[' . \implode('.', $sections) . ']';
                    $result[] = $this->render($dataValue, $sections);
                } else {
                    foreach ($dataValue as $key => $value) {
                        $result[] = $dataKey . '[' . $key . '] = "' . \str_replace('"', '\"', (string)$value) . '"';
                    }
                }
            } else {
                $result[] = $dataKey . ' = "' . \str_replace('"', '\"', (string)$dataValue) . '"';
            }
        }

        return \implode(Data::LE, $result);
    }
}
