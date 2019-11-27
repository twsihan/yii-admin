<?php

namespace twsihan\admin\models\form;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\components\rbac\DbManager;
use twsihan\admin\models\entity\ItemEntity;
use Yii;
use yii\db\Query;

/**
 * Class ItemForm
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class ItemForm extends ItemEntity
{
    public $type;
    public $parentName;
    public $name;
    public $description;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        $authManager = ParamsHelper::getAuthManager();
        return [
            [
                'parentName',
                function ($attribute) use ($authManager) {
                    $exists = (new Query())->from($authManager->itemTable)
                        ->where('name = :name and type = :type', [':name' => $this->$attribute, ':type' => 3])
                        ->exists();
                    if (!$exists) {
                        $this->addError($attribute, '属性不存在，请重新输入！~');
                    }
                },
            ],
            [
                ['name', 'description'],
                'required',
            ],
            [
                'name',
                function ($attribute) use ($authManager) {
                    $exists = (new Query())->from($authManager->itemTable)
                        ->where('name = :name and type = :type', [':name' => $this->$attribute, ':type' => 2])
                        ->exists();
                    if ($exists) {
                        $this->addError($attribute, '属性已存在，请重新输入！~');
                    }
                },
            ],
        ];
    }

    public function handle($id)
    {
        /* @var DbManager $authManager */
        $authManager = ParamsHelper::getAuthManager();
        if ($this->validate()) {
            if ($id) {
                if ($this->parentName) {
                    $parentName = null;
                    $itemGroup = static::itemGroup();
                    foreach ($itemGroup as $group => $items) {
                        foreach ($items['items'] as $item) {
                            if ($item['name'] == $id) {
                                $parentName = $group;
                                break;
                            }
                        }
                    }
                    if ($parentName !== null) { // 如果之前有组移除
                        $authManager->removeChild($authManager->createPermissionGroup($parentName), $authManager->getPermission($id));
                    }
                    $item = $authManager->createPermission($this->name);
                    $item->description = $this->description;
                    return $authManager->update($id, $item) && $authManager->addChild($authManager->createPermissionGroup($this->parentName), $item);
                } else {
                    $item = $authManager->createPermissionGroup($this->name);
                    $item->description = $this->description;
                    return $authManager->update($id, $item);
                }
            } else {
                if ($this->parentName) {
                    $item = $authManager->createPermission($this->name);
                    $item->description = $this->description;
                    return $authManager->add($item) && $authManager->addChild($authManager->createPermissionGroup($this->parentName), $item);
                } else {
                    $item = $authManager->createPermissionGroup($this->name);
                    $item->description = $this->description;
                    return $authManager->add($item);
                }
            }
        }
        return false;
    }
}
