<?php

namespace twsihan\admin\components\helpers;

use Yii;
use yii\base\Module;
use yii\db\ActiveRecord;
use yii\web\User;
use twsihan\admin\Module as AdminModule;
use twsihan\yii\helpers\ArrayHelper;

/**
 * Class ParamsHelper
 *
 * @package twsihan\admin\components\helpers
 * @author twsihan <twsihan@gmail.com>
 */
class ParamsHelper
{
    const ADMIN_PREFIX = 'twsihan.admin.';


    /**
     * adminRulePrefix
     * @return string
     */
    public static function adminRulePrefix()
    {
        return trim(ArrayHelper::getValue(Yii::$app->params, static::ADMIN_PREFIX . 'prefix', 'admin'), '/');
    }

    /**
     * adminModule
     * @return Module|AdminModule|null
     */
    public static function adminModule()
    {
        $id = static::adminRulePrefix();

        return Yii::$app->getModule($id);
    }

    /**
     * getUser
     * @return string|User
     */
    public static function getUser()
    {
        /* @var AdminModule $module */
        $module = static::adminModule();

        return $module->getUser();
    }

    /**
     * getIdentity
     * @param null $name
     * @return mixed|\yii\web\IdentityInterface|null
     * @throws \Throwable
     */
    public static function getIdentity($name = null)
    {
        /* @var User $user */
        $user = static::getUser();

        return $name ? ArrayHelper::getValue($user->identity, $name) : $user->getIdentity();
    }

    /**
     * profileUrl
     * @param null $default
     * @return mixed
     */
    public static function profileUrl($default = null)
    {
        $default = $default ? $default : ('/' . static::adminRulePrefix() . '/admin/profile');

        return ArrayHelper::getValue(Yii::$app->params, static::ADMIN_PREFIX . 'profileUrl', $default);
    }

    /**
     * logoutUrl
     * @param null $default
     * @return mixed
     */
    public static function logoutUrl($default = null)
    {
        $default = $default ? $default : ('/' . static::adminRulePrefix() . '/default/logout');

        return ArrayHelper::getValue(Yii::$app->params, static::ADMIN_PREFIX . 'logoutUrl', $default);
    }
}
