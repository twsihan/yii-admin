<?php

namespace twsihan\admin\models\logic;

use Yii;
use yii\base\Model;
use yii\db\Query;

/**
 * Class AuthLogic
 *
 * @package twsihan\admin\models\logic
 * @author twsihan <twsihan@gmail.com>
 */
class AuthLogic extends Model
{


    protected static function getAuthManager()
    {
        return Yii::$app->getAuthManager();
    }

    public static function isRole($name)
    {
        $auth = static::getAuthManager();
        return (new Query())->from($auth->assignmentTable)
            ->where('item_name = :name', [':name' => $name])
            ->count('item_name');
    }
}
