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

use JBZoo\Data\Data;
use JBZoo\Data\Ini;
use JBZoo\Data\JSON;
use JBZoo\Data\PhpArray;
use JBZoo\Data\Yml;

use function JBZoo\Data\data;
use function JBZoo\Data\ini;
use function JBZoo\Data\json;
use function JBZoo\Data\phpArray;
use function JBZoo\Data\yml;

/**
 * @BeforeMethods({"init"})
 * @Revs(100000)
 * @Iterations(3)
 */
class CreateObject
{
    protected array $data = [];

    public function init(): void
    {
        $this->data = DataFixture::createRandomArray();
    }

    /**
     * @Groups({"Native", "ArrayObject"})
     */
    public function benchArrayObjectOrig(): void
    {
        new \ArrayObject($this->data);
    }

    /**
     * @Groups({"Native", "ArrayObject", "Extended"})
     */
    public function benchArrayObjectExtOrig(): void
    {
        new \ArrayObjectExt($this->data);
    }

    /**
     * @Groups({"Data"})
     */
    public function benchData(): void
    {
        new Data($this->data);
    }

    /**
     * @Groups({"PhpArray"})
     */
    public function benchPhpArray(): void
    {
        new PhpArray($this->data);
    }

    /**
     * @Groups({"Ini"})
     */
    public function benchIni(): void
    {
        new Ini($this->data);
    }

    /**
     * @Groups({"JSON"})
     */
    public function benchJson(): void
    {
        new JSON($this->data);
    }

    /**
     * @Groups({"Yml"})
     */
    public function benchYml(): void
    {
        new Yml($this->data);
    }

    /**
     * @Groups({"Data", "Func"})
     */
    public function benchDataFunc(): void
    {
        data($this->data);
    }

    /**
     * @Groups({"PhpArray", "Func"})
     */
    public function benchPhpArrayFunc(): void
    {
        phpArray($this->data);
    }

    /**
     * @Groups({"Ini", "Func"})
     */
    public function benchIniFunc(): void
    {
        ini($this->data);
    }

    /**
     * @Groups({"JSON", "Func"})
     */
    public function benchJsonFunc(): void
    {
        json($this->data);
    }

    /**
     * @Groups({"Yml", "Func"})
     */
    public function benchYmlFunc(): void
    {
        yml($this->data);
    }
}
