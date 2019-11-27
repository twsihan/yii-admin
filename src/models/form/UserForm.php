<?php

namespace twsihan\admin\models\form;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\components\web\UploadedFile;
use twsihan\admin\models\entity\UserEntity;
use twsihan\yii\helpers\FileHelper;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\web\IdentityInterface;

/**
 * Class UserForm
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class UserForm extends UserEntity
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
                    'roleName',
                ],
                'required',
            ],
            [
                [
                    'avatar',
                ],
                'file',
            ],
            [
                [
                    'email',
                    'address',
                    'status',
                ],
                'safe',
            ],
        ];
    }

    public function handle($id)
    {
        $result = false;
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $webUser = ParamsHelper::getUser();
                /* @var IdentityInterface $class */
                $class = $webUser->identityClass;
                /* @var ActiveRecord $model */
                if ($id) {
                    $model = $class::findIdentity($id);
                } else {
                    $model = new $class();
                    $model->setAttributes([
                        'username' => $this->username,
                    ], false);
                }
                $model->setAttributes([
                    'email' => $this->email,
                    'real_name' => $this->realName,
                    'address' => $this->address,
                ], false);
                if ($this->status) {
                    $model->setAttribute('status', $this->status);
                }
                if ($this->password) {
                    $password = Yii::$app->security->generatePasswordHash($this->password);
                    $model->setAttribute('password_hash', $password);
                }
                /* @var UploadedFile $uploadModel */
                $uploadModel = $this->getUploadModel();
                $upload = $uploadModel::getInstance($this, 'avatar');
                $avatar = null;
                if ($upload) {
                    $avatar = $model->getAttribute('avatar');
                    $model->setAttribute('avatar', '/avatar/' . md5($upload->baseName) . '.' . $upload->extension);
                    $upload->saveAs($model->getAttribute('avatar'));
                }
                if ($model->save()) {
                    if ($this->roleName) {
                        $auth = Yii::$app->getAuthManager();
                        $auth->revokeAll($id);
                        if ($auth->assign($auth->createRole($this->roleName), $model->getPrimaryKey()) && $avatar) {
                            FileHelper::unlink(Yii::getAlias($uploadModel->getUpload() . $avatar));
                        }
                    }
                    $transaction->commit();
                    $result = true;
                } else {
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
        return $result;
    }
}
