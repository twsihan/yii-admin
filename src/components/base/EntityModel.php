<?php

namespace twsihan\admin\components\base;

use yii\base\Model;

/**
 * Class EntityModel
 *
 * @package twsihan\admin\components\base
 * @author twsihan <twsihan@gmail.com>
 */
class EntityModel extends Model
{
    public $limit = 20;

    private $_serializeHandle;


    /**
     * @return callable
     */
    public function getSerializeHandle(): callable
    {
        return $this->_serializeHandle;
    }

    /**
     * @param mixed $serializeHandle
     */
    public function setSerializeHandle($serializeHandle): void
    {
        $this->_serializeHandle = $serializeHandle;
    }
}
