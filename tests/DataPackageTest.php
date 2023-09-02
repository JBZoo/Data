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

use JBZoo\Markdown\Table;

final class DataPackageTest extends \JBZoo\Codestyle\PHPUnit\AbstractPackageTest
{
    protected string $packageName = 'Data';

    protected function setUp(): void
    {
        parent::setUp();
        $this->excludedPathsForCopyrights[] = 'resource';
    }

    public function testComparativetablelInReadme(): void
    {
        $rows = [
            [
                'Create',
                '`$d = data($someData)`',
                '`$ar = [/* ... */];`',
            ],
            [
                'Supported formats',
                'Array, Object, ArrayObject, JSON, INI, Yml',
                'Array',
            ],
            [
                'Load form file',
                '*.php, *.ini, *.yml, *.json, serialized',
                '-',
            ],
            [
                'Get value or default',
                "`\$d->get('key', 42)`",
                "`\$ar['key'] ?? 42`",
            ],
            [
                'Get undefined #1',
                "`\$d->get('undefined')` (no any notice)",
                "`\$ar['undefined'] ?? null`",
            ],
            [
                'Get undefined #2',
                '`$d->find(\'undefined\')`',
                '`$ar[\'und\'] ??  null`',
            ],
            [
                'Get undefined #3',
                '`$d->undefined === null` (no any notice)',
                '-',
            ],
            [
                'Get undefined #4',
                "`\$d['undefined'] === null` (no any notice)",
                '-',
            ],
            [
                'Get undefined #5',
                "`\$d['undef']['undef'] === null` (no any notice)",
                '-',
            ],
            [
                'Comparing #1',
                "`\$d->get('key') === \$someVar`",
                '`$ar[\'key\'] === $someVar`',
            ],
            [
                'Comparing #2',
                "`\$d->is('key', \$someVar)`",
                '-',
            ],
            [
                'Comparing #3',
                "`\$d->is('key', \$someVar, true)` (strict)",
                '-',
            ],
            [
                'Like array',
                "`\$d['key']`",
                "`\$ar['key']`",
            ],
            [
                'Like object #1',
                '`$d->key`',
                '-',
            ],
            [
                'Like object #2',
                "`\$d->get('key')`",
                '-',
            ],
            [
                'Like object #3',
                "`\$d->find('key')`",
                '-',
            ],
            [
                'Like object #4',
                '`$d->offsetGet(\'key\')`',
                '-',
            ],
            [
                'Isset #1',
                "`isset(\$d['key'])`",
                "`isset(\$ar['key'])`",
            ],
            [
                'Isset #2',
                '`isset($d->key)`',
                "`array_key_exists('key', \$ar)`",
            ],
            [
                'Isset #3',
                "`\$d->has('key')`",
                '-',
            ],
            [
                'Nested key  #1',
                '`$d->find(\'inner.inner.prop\', $default)`',
                "`\$ar['inner']['inner']['prop']` (error?)",
            ],
            [
                'Nested key  #2',
                "`\$d->inner['inner']['prop']`",
                '-',
            ],
            [
                'Nested key  #3',
                "`\$d['inner']['inner']['prop']`",
                '-',
            ],
            [
                'Export to Serialized',
                '`echo (new Data([/* ... */]))`',
                '`echo serialize([/* ... */])`',
            ],
            [
                'Export to JSON',
                '`echo (new JSON([/* ... */]))` (readable)',
                '`echo json_encode([/* ... */])`',
            ],
            [
                'Export to Yml',
                '`echo (new Yml ([/* ... */]))` (readable)',
                '-',
            ],
            [
                'Export to Ini',
                '`echo (new Ini([/* ... */]))` (readable)',
                '-',
            ],
            [
                'Export to PHP Code',
                '`echo (new PHPArray ([/* ... */]))` (readable)',
                '-',
            ],
            [
                'JSON',
                '**+**',
                '-',
            ],
            [
                'Filters',
                '**+**',
                '-',
            ],
            [
                'Search',
                '**+**',
                '-',
            ],
            [
                'Flatten Recursive',
                '**+**',
                '-',
            ],
            [
                'Set Value',
                "`\$d['value'] = 42`",
                "\$ar['value'] = 42",
            ],
            [
                'Set Nested Value',
                "`\$d->set('q.w.e.r.t.y') = 42`",
                "\$ar['q']['w']['e']['r']['t']['y'] = 42",
            ],
            [
                "Set Nested Value (if it's undefined)",
                "`\$d->set('q.w.e.r.t.y') = 42`",
                'PHP Notice errors...',
            ],
        ];

        $table = (new Table())
            ->setHeaders(['Action', 'JBZoo/Data', 'Pure PHP way'])
            ->setAlignments([Table::ALIGN_LEFT, Table::ALIGN_LEFT, Table::ALIGN_LEFT])
            ->appendRows($rows);

        isFileContains($table->render(), PROJECT_ROOT . '/README.md');
    }
}
