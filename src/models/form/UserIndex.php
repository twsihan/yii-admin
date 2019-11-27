<?php

namespace twsihan\admin\models\form;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\models\entity\UserEntity;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * Class UserIndex
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class UserIndex extends UserEntity
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
                    'email',
                    'roleName',
                ],
                'safe',
            ],
        ];
    }

    public function handle()
    {
        $webUser = ParamsHelper::getUser();
        /* @var ActiveRecord $class */
        $class = $webUser->identityClass;
        $authManager = ParamsHelper::getAuthManager();
        $query = (new Query())->from(['u' => $class::tableName()])
            ->leftJoin(['aa' => $authManager->assignmentTable], 'id = user_id');
        if ($this->validate()) {
            if ($this->username) {
                $query->andWhere('u.username like :username', [':username' => "%{$this->username}%"]);
            }
            if ($this->email) {
                $query->andWhere('u.email like :email', [':email' => "%{$this->email}%"]);
            }
            if ($this->roleName) {
                $query->andWhere('aa.item_name = :roleName', [':roleName' => $this->roleName]);
            }
        }
        $query->orderBy(['u.created_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => $this->limit,
            ],
        ]);
        return $dataProvider;
    }
}
