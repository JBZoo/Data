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
 * Class GetValue
 * @BeforeMethods({"init"})
 * @Revs(1000000)
 * @Iterations(3)
 */
class GetValue
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
     * @Groups({"Native", "Array"})
     */
    public function benchArrayRegular()
    {
        return $this->array['prop'];
    }

    /**
     * @Groups({"Native", "Array"})
     */
    public function benchArrayRegularMuted()
    {
        return @$this->array['prop'];
    }

    /**
     * @Groups({"Native", "Array"})
     */
    public function benchArrayIsset()
    {
        return $this->array['prop'] ?? null;
    }

    /**
     * @Groups({"Native", "ArrayObject"})
     */
    public function benchArrayObjectArray()
    {
        return $this->arrObj['prop'];
    }

    /**
     * @Groups({"Native", "ArrayObject"})
     */
    public function benchArrayObjectOffsetGet()
    {
        return $this->arrObj->offsetGet('prop');
    }

    /**
     * @Groups({"Native", "ArrayObject", "Extended"})
     */
    public function benchArrayObjectArrayExt()
    {
        return $this->arrObjExt['prop'];
    }

    /**
     * @Groups({"Native", "ArrayObject", "Extended"})
     */
    public function benchArrayObjectExtOffsetGet()
    {
        return $this->arrObjExt->offsetGet('prop');
    }

    /**
     * @Groups({"Data"})
     */
    public function benchDataGet()
    {
        return $this->data->get('prop');
    }

    /**
     * @Groups({"Data"})
     */
    public function benchDataArrow()
    {
        return $this->data->prop;
    }

    /**
     * @Groups({"Data"})
     */
    public function benchDataArray()
    {
        return $this->data['prop'];
    }

    /**
     * @Groups({"Data"})
     */
    public function benchDataFind()
    {
        return $this->data->find('prop');
    }

    /**
     * @Groups({"Data"})
     */
    public function benchDataOffsetGet()
    {
        return $this->data->offsetGet('prop');
    }
}
