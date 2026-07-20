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

namespace JBZoo\PHPUnit;

use JBZoo\Data\Yml;

class DataYmlTest extends PHPUnit
{
    protected string $testFile = './tests/resource/data.yml';

    public function testFile(): void
    {
        $data     = new Yml($this->testFile);
        $reParsed = new Yml((string)$data);

        // symfony/yaml's dump formatting changed across majors (7.x block vs 8.x inline sequences),
        // so assert the DATA survives a dump -> parse round-trip instead of matching the source file
        // byte-for-byte (which is brittle across the PHP/symfony matrix).
        isSame($data->getArrayCopy(), $reParsed->getArrayCopy());

        // Concrete fixture values (tests/resource/data.yml) guard against a SYMMETRIC parse/dump loss
        // that a pure round-trip cannot see — a bug dropping the same field on both sides still
        // compares equal. Assert both the loaded and the reparsed value.
        isSame(34843, $data->get('invoice'));
        isSame('BL4438H', $reParsed->find('product.1.sku'));
    }

    public function testString(): void
    {
        $data     = new Yml(openFile($this->testFile));
        $reParsed = new Yml((string)$data);

        isSame($data->getArrayCopy(), $reParsed->getArrayCopy());
    }

    public function testPropsVisible(): void
    {
        $data = new Yml(openFile($this->testFile));
        isTrue(\count((array)$data) > 0);
    }
}
