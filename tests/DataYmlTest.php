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
        $data      = new Yml($this->testFile);
        $dataValid = openFile($this->testFile);

        is($dataValid, (string)$data);
    }

    public function testString(): void
    {
        $data      = new Yml(openFile($this->testFile));
        $dataValid = openFile($this->testFile);

        is($dataValid, (string)$data);
    }

    public function testPropsVisible(): void
    {
        $data = new Yml(openFile($this->testFile));
        isTrue(\count((array)$data) > 0);
    }
}
