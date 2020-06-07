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

/**
 * Class GetUndefinedValue
 * @BeforeMethods({"init"})
 * @Revs(1000000)
 * @Iterations(3)
 */
class GetUndefinedValue
{
    /**
     * @var array
     */
    protected $array = [];

    /**
     * @var Data
     */
    protected $data = [];

    /**
     * @var ArrayObject|null
     */
    protected $arrObj;

    /**
     * @var ArrayObjectExt|null
     */
    protected $arrObjExt;

    public function init(): void
    {
        $this->array = Fixture::createRandomArray();
        $this->data = new Data($this->array);
        $this->arrObj = new \ArrayObject($this->array);
        $this->arrObjExt = new \ArrayObjectExt($this->array);
    }

    /**
     * @Groups({"Native", "Array", "Undefined"})
     */
    public function benchArrayRegularMuted()
    {
        return @$this->array['undefined'];
    }

    /**
     * @Groups({"Native", "Array", "Undefined"})
     */
    public function benchArrayIsset()
    {
        return $this->array['undefined'] ?? null;
    }

    /**
     * @Groups({"Data", "Undefined"})
     */
    public function benchDataGet()
    {
        return $this->data->get('undefined');
    }

    /**
     * @Groups({"Data", "Undefined"})
     */
    public function benchDataArrow()
    {
        return $this->data->undefined;
    }

    /**
     * @Groups({"Data", "Undefined"})
     */
    public function benchDataArray()
    {
        return $this->data['undefined'];
    }

    /**
     * @Groups({"Data", "Undefined"})
     */
    public function benchDataFind()
    {
        return $this->data->find('undefined');
    }

    /**
     * @Groups({"Data", "Undefined"})
     */
    public function benchDataFindInner()
    {
        return $this->data->find('undefined.inner');
    }

    /**
     * @Groups({"Data", "Undefined"})
     */
    public function benchDataOffsetGet()
    {
        return $this->data->offsetGet('undefined');
    }
}
