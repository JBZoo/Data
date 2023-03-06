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

use JBZoo\Data\Ini;

class DataIniTest extends PHPUnit
{
    protected string $testFile = './tests/resource/data.ini';

    public function testFile(): void
    {
        $data      = new Ini($this->testFile);
        $dataValid = openFile($this->testFile);

        is($dataValid, (string)$data);
    }

    public function testString(): void
    {
        $data      = new Ini(openFile($this->testFile));
        $dataValid = openFile($this->testFile);

        isContain($dataValid, (string)$data . "\n");
    }

    public function testPropsVisible(): void
    {
        $data = new Ini(openFile($this->testFile));
        isTrue(\count((array)$data) > 0);
    }
}
