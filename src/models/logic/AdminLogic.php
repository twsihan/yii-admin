<?php

namespace twsihan\admin\models\logic;

use twsihan\yii\helpers\ArrayHelper;
use twsihan\yii\helpers\FileHelper;
use twsihan\admin\models\mysql\Admin;
use twsihan\admin\models\mysql\AuthAssignment;
use twsihan\admin\models\form\AdminForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\rbac\DbManager;

/**
 * Class AdminLogic
 *
 * @package twsihan\admin\models\logic
 * @author twsihan <twsihan@gmail.com>
 */
class AdminLogic extends AdminForm
{


    public function findByUserId($userId)
    {
        $admin = Admin::findByUserId($userId, true);
        $this->setAttributes($admin, false);
        $this->realName = ArrayHelper::getValue($admin, 'real_name');
        $this->roleName = ArrayHelper::getValue($admin, 'assign.item_name');
    }

    public function create()
    {
        $result = false;
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model = new Admin();
                $uploadModel = $this->getUploadModel();
                $upload = $uploadModel::getInstance($this, 'avatar');
                if ($upload) {
                    $avatar = 'avatar/' . md5($upload->baseName) . '.' . $upload->extension;
                    $upload->saveAs($avatar);
                } else {
                    $avatar = '';
                }

                $model->setAttributes([
                    'username' => $this->username,
                    'email' => $this->email,
                    'real_name' => $this->realName,
                    'avatar' => $avatar,
                    'address' => $this->address,
                    'status' => $this->status,
                ], false);
                $model->setPassword($this->password);
                if ($model->save()) {
                    /** @var DbManager $auth */
                    $auth = Yii::$app->getAuthManager();
                    if ($auth->assign($auth->createRole($this->roleName), $model->getPrimaryKey())) {
                        $transaction->commit();
                        $result = true;
                    }
                } else {
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
        return $result;
    }

    public function update($userId)
    {
        $result = false;
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model = Admin::findByUserId($userId);
                if ($model) {
                    $model->setAttributes([
                        'username' => $this->username,
                        'email' => $this->email,
                        'real_name' => $this->realName,
                        'address' => $this->address,
                    ], false);
                    if ($this->status) {
                        $model->setAttribute('status', $this->status);
                    }
                    if ($this->password) {
                        $model->setPassword($this->password);
                    }
                    $uploadModel = $this->getUploadModel();
                    $upload = $uploadModel::getInstance($this, 'avatar');
                    $avatar = null;
                    if ($upload) {
                        $avatar = $model->getAttribute('avatar');
                        $model->setAttribute('avatar', 'avatar/' . md5($upload->baseName) . '.' . $upload->extension);
                        $upload->saveAs($model->getAttribute('avatar'));
                    }
                    if ($model->save()) {
                        if ($this->roleName) {
                            $auth = Yii::$app->getAuthManager();
                            $auth->revokeAll($userId);
                            if ($auth->assign($auth->createRole($this->roleName), $model->getPrimaryKey()) && $avatar) {
                                FileHelper::unlink(Yii::getAlias($uploadModel->getUpload() . $avatar));
                            }
                        }
                        $transaction->commit();
                        $result = true;
                    } else {
                        $transaction->rollBack();
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
        return $result;
    }

    /**
     * 密码
     * @param int $userId
     * @return bool
     */
    public function password($userId)
    {
        $result = false;
        if ($this->validate()) {
            $model = Admin::findByUserId($userId);
            if ($model) {
                if ($this->cPassword) {
                    $model->setPassword($this->cPassword);
                }
                if ($model->save()) {
                    $result = true;
                }
            }
        }
        return $result;
    }

    /**
     * 搜索列表
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = Admin::find()->alias('a')
            ->leftJoin(['aa' => AuthAssignment::tableName()], 'a.id = aa.user_id');

        if ($this->validate()) {
            if ($this->username) {
                $query->andWhere('a.username like :username', [':username' => "%{$this->username}%"]);
            }
            if ($this->email) {
                $query->andWhere('a.email like :email', [':email' => "%{$this->email}%"]);
            }
            if ($this->roleName) {
                $query->andWhere('aa.item_name = :roleName', [':roleName' => $this->roleName]);
            }
        }

        $query->orderBy(['a.created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 10,
            ],
        ]);

        return $dataProvider;
    }

    /**
     * 获取列表 Key->Value
     * @param string $to
     * @return array
     */
    public static function select($to = 'username')
    {
        return ArrayHelper::map(Admin::find()->asArray()->all(), 'id', $to);
    }
}
