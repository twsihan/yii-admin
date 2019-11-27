<?php

namespace twsihan\admin\models\form;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\models\entity\UserEntity;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class UserLogin
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class UserLogin extends UserEntity
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'username',
                    'password',
                ],
                'required',
            ],
        ];
    }

    /**
     * login
     * @return array|bool
     * @throws InvalidConfigException
     * @throws UnprocessableEntityHttpException
     * @throws \yii\base\Exception
     */
    public function login()
    {
        if ($this->validate()) {
            $webUser = ParamsHelper::getUser();
            /* @var ActiveRecord $class */
            $class = $webUser->identityClass;
            $user = $class::findOne(['username' => $this->username]);
            if ($user && $user->validatePassword($this->password)) {
                $accessTokenKey = ParamsHelper::accessTokenParam();
                $accessTokenVal = Yii::$app->security->generateRandomString();
                $class::updateAll(
                    [
                        $accessTokenKey => $accessTokenVal,
                        'last_time' => time(),
                        'last_ip' => Yii::$app->request->userIP,
                    ], [
                        'username' => $this->username,
                    ]
                );
                $this->_user = $class::findOne(['username' => $this->username]);
                $accessTokenExpire = ParamsHelper::accessTokenExpire();
                if ($webUser->login($this->_user, $this->rememberMe ? $accessTokenExpire : 0)) {
                    return [$accessTokenKey => $accessTokenVal];
                };
            } else {
                throw new UnprocessableEntityHttpException('密码错误');
            }
        }
        return false;
    }
}
