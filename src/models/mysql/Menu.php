<?php

namespace twsihan\admin\models\mysql;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Menu
 *
 * @package twsihan\admin\models\mysql
 * @author twsihan <twsihan@gmail.com>
 */
class Menu extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
}
