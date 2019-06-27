<?php

namespace twsihan\admin\models\mysql;

use yii\db\ActiveRecord;

/**
 * Class AuthAssignment
 *
 * @package twsihan\admin\models\mysql
 * @author twsihan <twsihan@gmail.com>
 */
class AuthAssignment extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public function getItem()
    {
        return $this->hasOne(AuthItem::class, ['name' => 'item_name']);
    }
}
