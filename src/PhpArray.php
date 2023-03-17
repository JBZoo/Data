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

final class PhpArray extends AbstractData
{
    /**
     * @param null|false|string|string[] $data The data array
     */
    public function __construct($data = [])
    {
        if (\is_string($data) && $data !== '' && \file_exists($data)) {
            $data = $this->decode($data);
        }

        if ($data === false || $data === null) {
            $data = [];
        }

        parent::__construct($data);
    }

    protected function decode(string $string): mixed
    {
        if (\file_exists($string)) {
            return include $string;
        }

        return [];
    }

    protected function encode(array $data): string
    {
        $data = [
            '<?php',
            '',
            'declare(strict_types=1);',
            '',
            'return ' . \var_export($data, true) . ';',
        ];

        return \implode(self::LE, $data);
    }
}
