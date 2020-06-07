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
 * @author     Denis Smetannikov <denis@jbzoo.com>
 */

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
 * Class CreateObject
 * @BeforeMethods({"init"})
 * @Revs(100000)
 * @Iterations(3)
 */
class CreateObject
{
    /**
     * @var array
     */
    protected $data = [];

    public function init(): void
    {
        $this->data = Fixture::createRandomArray();
    }

    /**
     * @Groups({"Native", "ArrayObject"})
     */
    public function benchArrayObjectOrig()
    {
        new \ArrayObject($this->data);
    }

    /**
     * @Groups({"Native", "ArrayObject", "Extended"})
     */
    public function benchArrayObjectExtOrig()
    {
        new \ArrayObjectExt($this->data);
    }

    /**
     * @Groups({"Data"})
     */
    public function benchData()
    {
        new Data($this->data);
    }

    /**
     * @Groups({"PhpArray"})
     */
    public function benchPhpArray()
    {
        new PhpArray($this->data);
    }

    /**
     * @Groups({"Ini"})
     */
    public function benchIni()
    {
        new Ini($this->data);
    }

    /**
     * @Groups({"JSON"})
     */
    public function benchJson()
    {
        new JSON($this->data);
    }

    /**
     * @Groups({"Yml"})
     */
    public function benchYml()
    {
        new Yml($this->data);
    }

    /**
     * @Groups({"Data", "Func"})
     */
    public function benchDataFunc()
    {
        data($this->data);
    }

    /**
     * @Groups({"PhpArray", "Func"})
     */
    public function benchPhpArrayFunc()
    {
        phpArray($this->data);
    }

    /**
     * @Groups({"Ini", "Func"})
     */
    public function benchIniFunc()
    {
        ini($this->data);
    }

    /**
     * @Groups({"JSON", "Func"})
     */
    public function benchJsonFunc()
    {
        json($this->data);
    }

    /**
     * @Groups({"Yml", "Func"})
     */
    public function benchYmlFunc()
    {
        yml($this->data);
    }
}
