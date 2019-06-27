<?php

namespace twsihan\admin\models\logic;

use twsihan\yii\helpers\ArrayHelper;
use twsihan\admin\components\rbac\DbManager;
use twsihan\admin\components\rbac\Item;
use twsihan\admin\models\form\ItemForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class ItemLogic
 *
 * @package twsihan\admin\models\logic
 * @author twsihan <twsihan@gmail.com>
 */
class ItemLogic extends ItemForm
{


    /**
     * typeMap
     * @param null $key
     * @return array
     */
    public static function typeMap($key = null)
    {
        $map = [
            Item::TYPE_PERMISSION => '权限列表',
            Item::TYPE_PERMISSION_GROUP => '权限组类',
        ];

        return !is_null($key) ? ArrayHelper::getValue($map, $key) : $map;
    }

    /**
     * @param string $name
     * @return array
     */
    public static function getParentSelect($name = '')
    {
        $auth = Yii::$app->getAuthManager();
        $result = (new Query())->from($auth->itemTable)
            ->where(['type' => 3])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $select = ArrayHelper::map($result, 'name', 'description');

        return isset($select[$name]) ? $select[$name] : $select;
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function create()
    {
        if ($this->validate()) {
            /* @var DbManager $auth */
            $auth = Yii::$app->authManager;
            if ($this->parentName) {
                $item = $auth->createPermission($this->name);
                $item->description = $this->description;
                return $auth->add($item) && $auth->addChild($auth->createPermissionGroup($this->parentName), $item);
            } else {
                $item = $auth->createPermissionGroup($this->name);
                $item->description = $this->description;
                return $auth->add($item);
            }
        }
        return false;
    }

    public function delete($name)
    {
        /* @var DbManager $auth */
        $auth = Yii::$app->authManager;
        if ($auth->getPermissionGroup($name) != null) {
            $group = $auth->getChildPermissionGroup($name);
            foreach ($group as $itemName => $val) {
                if ($val === true) {
                    $item = $auth->createPermission($itemName);
                    $auth->remove($item);
                }
            }
            $item = $auth->createPermissionGroup($name);
            return $auth->remove($item);
        } else {
            $item = $auth->createPermission($name);
            return $auth->remove($item);
        }
    }

    public function reload($name)
    {
        /* @var DbManager $auth */
        $auth = Yii::$app->authManager;
        $itemGroup = static::itemGroup();
        foreach ($itemGroup as $group => $items) {
            foreach ($items['items'] as $item) {
                if ($item['name'] == $name) {
                    $this->parentName = $group;
                    break;
                }
            }
        }
        if ($this->parentName) {
            $this->setAttributes(ArrayHelper::toArray($auth->getPermission($name)), false);
        } else {
            $this->setAttributes(ArrayHelper::toArray($auth->getPermissionGroup($name)), false);
        }
    }

    /**
     * @param $name
     * @return bool
     * @throws \Exception
     */
    public function update($name)
    {
        if ($this->validate()) {
            /* @var DbManager $auth */
            $auth = Yii::$app->authManager;

            $parentName = null;
            $itemGroup = static::itemGroup();
            foreach ($itemGroup as $group => $items) {
                foreach ($items['items'] as $item) {
                    if ($item['name'] == $name) {
                        $parentName = $group;
                        break;
                    }
                }
            }

            if ($parentName !== null) { // 如果之前有组移除
                $auth->removeChild($auth->createPermissionGroup($parentName), $auth->getPermission($name));
            }

            if ($this->parentName) {
                $item = $auth->createPermission($this->name);
                $item->description = $this->description;
                return $auth->update($name, $item) && $auth->addChild($auth->createPermissionGroup($this->parentName), $item);
            } else {
                $item = $auth->createPermissionGroup($this->name);
                $item->description = $this->description;
                return $auth->update($name, $item);
            }
        }
        return false;
    }

    /**
     * @return ActiveDataProvider
     */
    public function search()
    {
        $auth = Yii::$app->getAuthManager();
        $parents = (new Query())->from($auth->itemTable)
            ->where(['type' => [Item::TYPE_ROLE]])->all();

        $query = (new Query())->from("{$auth->itemTable} a")
            ->select([
                'a.name AS name',
                'a.type AS type',
                'a.description AS description',
                'c.name AS parentName',
                'c.description AS parentDescription',
                'a.updated_at AS update_time',
                'a.created_at AS create_time',
            ])->leftJoin("{$auth->itemChildTable} b", 'a.name = b.child')
            ->leftJoin("{$auth->itemTable} c", 'b.parent = c.name');

        $query->orWhere(['in', 'a.type', [Item::TYPE_ROLE, Item::TYPE_PERMISSION]]);
        $query->orWhere(['in', 'b.parent', ArrayHelper::getColumn($parents, 'name')]);
        return new ActiveDataProvider(['query' => $query]);
    }

    /**
     * @return array
     */
    public static function itemGroup()
    {
        $auth = Yii::$app->getAuthManager();
        $parents = (new Query())->from($auth->itemTable)
            ->where(['type' => [Item::TYPE_PERMISSION_GROUP]])
            ->all();

        $itemGroup = [];
        foreach ($parents as $parent) {
            $query = (new Query())->from("{$auth->itemTable} a")
                ->leftJoin("{$auth->itemChildTable} b", 'a.name = b.child')
                ->where('b.parent = :parent', [':parent' => $parent['name']])
                ->all();

            $parent['items'] = $query;
            $itemGroup[$parent['name']] = $parent;
        }

        return $itemGroup;
    }
}
