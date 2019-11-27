<?php

namespace twsihan\admin\models\form;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\models\entity\UserEntity;
use Yii;
use yii\web\IdentityInterface;

/**
 * Class UserPassword
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class UserPassword extends UserEntity
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'password',
                    'cPassword'
                ],
                'required',
            ],
            ['cPassword', 'confirmPassword'],
        ];
    }

    public function confirmPassword($attribute)
    {
        if ($this->password !== $this->cPassword) {
            $this->addError($attribute, '密码不一致');
        }
    }

    public function handle($id)
    {
        $result = false;
        if ($this->validate()) {
            $webUser = ParamsHelper::getUser();
            /* @var IdentityInterface $class */
            $class = $webUser->identityClass;
            $model = $class::findIdentity($id);
            if ($model) {
                $password = Yii::$app->security->generatePasswordHash($this->password);
                $model->setAttribute('password_hash', $password);
                if ($model->save()) {
                    $result = true;
                }
            }
        }
        return $result;
    }
}
