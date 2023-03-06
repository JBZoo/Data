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

use JBZoo\Data\PhpArray;

class DataPhpArrayTest extends PHPUnit
{
    protected string $testFile = './tests/resource/data.inc';

    public function testFile(): void
    {
        $data      = new PhpArray($this->testFile);
        $dataValid = include $this->testFile;

        is($dataValid, $data->getArrayCopy());
    }

    public function testPropsVisible(): void
    {
        $data = new PhpArray($this->testFile);
        isTrue(\count((array)$data) > 0);
    }

    public function testToString(): void
    {
        $data = new PhpArray($this->testFile);

        isSame(\implode(\PHP_EOL, [
            '<?php',
            '',
            'declare(strict_types=1);',
            '',
            'return array (',
            "  'host' => 'localhost',",
            "  'null' => NULL,",
            "  'port' => 80,",
            "  'servers' => ",
            '  array (',
            "    0 => 'host1',",
            "    1 => 'host2',",
            "    2 => 'host3',",
            '  ),',
            "  'application' => ",
            '  array (',
            "    'name' => 'configuration',",
            "    'secret' => 's3cr3t',",
            "    'null' => NULL,",
            "    'false' => false,",
            "    'true' => true,",
            "    'array' => ",
            '    array (',
            '    ),',
            '  ),',
            ');',
        ]), (string)$data);
    }
}
