<?php

namespace twsihan\admin\components\helpers;

use twsihan\admin\components\rbac\DbManager;
use twsihan\admin\models\mysql\Menu;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\User;
use twsihan\admin\Module;
use twsihan\yii\helpers\ArrayHelper;

/**
 * Class ParamsHelper
 *
 * @package twsihan\admin\components\helpers
 * @author twsihan <twsihan@gmail.com>
 */
class ParamsHelper
{


    /**
     * getParam
     * @param $key
     * @param $default
     * @return string
     */
    public static function getParam($key, $default)
    {
        return trim(ArrayHelper::getValue(Yii::$app->params, 'twsihan.admin.' . $key, $default), '/');
    }

    /**
     * modulePrefix
     * @return string
     */
    public static function module()
    {
        return static::getParam('module', 'admin');
    }

    /**
     * getModule
     * @return Module
     */
    public static function getModule()
    {
        $id = static::module();
        /** @var Module $module */
        $module = Yii::$app->getModule($id);
        return $module;
    }

    /**
     * getUser
     * @return User
     * @throws InvalidConfigException
     */
    public static function getUser()
    {
        $id = static::getParam('user', 'user');
        /** @var User $user */
        $user = Yii::$app->get($id);
        return $user;
    }

    public static function accessTokenParam($default = 'access_token')
    {
        return static::getParam('accessTokenParam', $default);
    }

    public static function accessTokenExpire($default = 0)
    {
        return static::getParam('accessTokenExpire', $default);
    }

    /**
     * getAuthManager
     * @return DbManager
     * @throws InvalidConfigException
     */
    public static function getAuthManager()
    {
        $id = static::getParam('authManager', 'authManager');
        /** @var DbManager $authManager */
        $authManager = Yii::$app->get($id);
        if ($authManager instanceof DbManager) {
            return $authManager;
        }
        throw new InvalidConfigException('class instanceof for : ' . DbManager::class);
    }

    /**
     * getMenuClass
     * @return Menu
     * @throws InvalidConfigException
     */
    public static function getMenuClass()
    {
        /* @var Menu $class */
        $class = static::getParam('menuClass', Menu::class);
        if (class_exists($class)) {
            return $class;
        } else {
            throw new InvalidConfigException('class instanceof for : ' . ActiveRecord::class);
        }
    }
}
