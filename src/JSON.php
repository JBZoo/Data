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

final class JSON extends Data
{
    protected function decode(string $string): mixed
    {
        /** @noinspection JsonEncodingApiUsageInspection */
        return \json_decode($string, true, 512, \JSON_BIGINT_AS_STRING);
    }

    protected function encode(array $data): string
    {
        $result = \json_encode($data, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT | \JSON_BIGINT_AS_STRING);

        // @phpstan-ignore-next-line
        return $result ?: '';
    }
}
