<?php

/**
 * JBZoo Toolbox - Data
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Data
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/Data
 */

declare(strict_types=1);

$default = include __DIR__ . '/../vendor/jbzoo/codestyle/src/phan/default.php';

$config = array_merge($default, [
    'file_list' => [
        'src/functions.php'
    ],

    'directory_list' => [
        // project
        'src',

        // Libs
        'vendor/jbzoo/utils',
        'vendor/symfony/yaml',
    ]
]);

$config['plugins'][] = 'NotFullyQualifiedUsagePlugin';

return $config;
