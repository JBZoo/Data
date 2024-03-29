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

final class Data extends AbstractData
{
    protected function decode(string $string): mixed
    {
        /** @noinspection UnserializeExploitsInspection */
        return \unserialize($string, []);
    }

    protected function encode(array $data): string
    {
        return \serialize($data);
    }
}
