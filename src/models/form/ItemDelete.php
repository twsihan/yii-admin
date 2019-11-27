<?php

namespace twsihan\admin\models\form;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\components\rbac\DbManager;
use twsihan\admin\models\entity\ItemEntity;

/**
 * Class ItemDelete
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class ItemDelete extends ItemEntity
{


    public function handle($id)
    {
        /* @var DbManager $authManager */
        $authManager = ParamsHelper::getAuthManager();
        if ($authManager->getPermissionGroup($id) != null) {
            $group = $authManager->getChildPermissionGroup($id);
            foreach ($group as $itemName => $val) {
                if ($val === true) {
                    $item = $authManager->createPermission($itemName);
                    $authManager->remove($item);
                }
            }
            $item = $authManager->createPermissionGroup($id);
            return $authManager->remove($item);
        } else {
            $item = $authManager->createPermission($id);
            return $authManager->remove($item);
        }
    }
}
