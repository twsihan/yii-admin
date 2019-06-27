<?php

namespace twsihan\admin\models\mysql;

use twsihan\admin\components\helpers\ParamsHelper;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\User;

/**
 * Class Admin
 *
 * @package twsihan\admin\models\mysql
 * @author twsihan <twsihan@gmail.com>
 */
class Admin extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 0;
    const STATUS_DELETED = 1;


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->getAttribute('auth_key');
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->getAttribute('password_hash'));
    }

    /**
     * Generates password hash from password and sets it to the model
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->setAttribute('password_hash', Yii::$app->security->generatePasswordHash($password));
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->setAttribute('auth_key', Yii::$app->security->generateRandomString());
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->setAttribute('password_reset_token', Yii::$app->security->generateRandomString() . '_' . time());
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->setAttribute('password_reset_token', '');
    }

    /**
     * {@inheritdoc}
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $this->generatePasswordResetToken();
        $this->generateAuthKey();

        return parent::save($runValidation, $attributeNames);
    }

    /**
     * {@inheritdoc}
     */
    public function getAssign()
    {
        return $this->hasOne(AuthAssignment::class, ['user_id' => 'id']);
    }

    /**
     * appUser
     * @return null|User
     * @throws \yii\base\InvalidConfigException
     */
    public static function appUser()
    {
        return ParamsHelper::getUser();
    }

    public static function findByUserId($userId, $asArray = false)
    {
        return static::find()->with('assign')
            ->where('id = :id', [':id' => $userId])
            ->asArray($asArray)->one();
    }
}
