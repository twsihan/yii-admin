<?php

namespace twsihan\admin\components\rbac;

use yii\base\InvalidArgumentException;
use yii\rbac\Permission;
use yii\rbac\Role;

/**
 * Class DbManager
 *
 * @package twsihan\admin\components\rbac
 * @author twsihan <twsihan@gmail.com>
 */
class DbManager extends \yii\rbac\DbManager
{


    /**
     * {@inheritdoc}
     */
    public function createPermissionGroup($name)
    {
        $permissionGroup = new PermissionGroup();
        $permissionGroup->name = $name;
        return $permissionGroup;
    }

    public function getPermissionGroup($name)
    {
        $item = $this->getItem($name);
        return $item instanceof Item && $item->type == Item::TYPE_PERMISSION_GROUP ? $item : null;
    }

    /**
     * Populates an auth item with the data fetched from database.
     * @param array $row the data from the auth item table
     * @return Item the populated auth item instance (either Role or Permission)
     */
    protected function populateItem($row)
    {
        if ($row['type'] == Item::TYPE_PERMISSION_GROUP) {
            $class = PermissionGroup::class;
        } else if ($row['type'] == Item::TYPE_PERMISSION) {
            $class = Permission::class;
        } else {
            $class = Role::class;
        }

        if (!isset($row['data']) || ($data = @unserialize(is_resource($row['data']) ? stream_get_contents($row['data']) : $row['data'])) === false) {
            $data = null;
        }

        return new $class([
            'name' => $row['name'],
            'type' => $row['type'],
            'description' => $row['description'],
            'ruleName' => $row['rule_name'] ?: null,
            'data' => $data,
            'createdAt' => $row['created_at'],
            'updatedAt' => $row['updated_at'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissionGroups()
    {
        return $this->getItems(Item::TYPE_PERMISSION_GROUP);
    }

    /**
     * {@inheritdoc}
     */
    public function getChildPermissionGroup($groupName)
    {
        $group = $this->getPermissionGroup($groupName);
        if ($group === null) {
            throw new InvalidArgumentException("Group \"$groupName\" not found.");
        }

        $result = [];
        $this->getChildrenRecursive($groupName, $this->getChildrenList(), $result);

        return $result;
    }
}
