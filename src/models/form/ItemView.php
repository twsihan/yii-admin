<?php

namespace twsihan\admin\models\form;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\components\rbac\DbManager;
use twsihan\admin\models\entity\ItemEntity;
use twsihan\yii\helpers\ArrayHelper;

/**
 * Class ItemIndex
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class ItemView extends ItemEntity
{


    public function handle($id)
    {
        /* @var DbManager $authManager */
        $authManager = ParamsHelper::getAuthManager();
        $itemGroup = static::itemGroup();
        foreach ($itemGroup as $group => $items) {
            foreach ($items['items'] as $item) {
                if ($item['name'] == $id) {
                    $this->parentName = $group;
                    break;
                }
            }
        }
        if ($this->parentName) {
            $this->setAttributes(ArrayHelper::toArray($authManager->getPermission($id)), false);
        } else {
            $this->setAttributes(ArrayHelper::toArray($authManager->getPermissionGroup($id)), false);
        }
        return $this;
    }
}
