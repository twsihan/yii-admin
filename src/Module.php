<?php

namespace twsihan\admin;

use yii\di\Instance;
use yii\web\User;

/**
 * Class Module
 *
 * @package twsihan\admin
 * @author twsihan <twsihan@gmail.com>
 */
class Module extends \yii\base\Module
{
    /**
     * @var User|string 指定用户
     */
    public $user = 'user';


    /**
     * getUser
     * @return User|string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!$this->user instanceof User) {
            $this->user = Instance::ensure($this->user, User::class);
        }
    }
}
