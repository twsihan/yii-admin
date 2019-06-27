<?php

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\yii\helpers\ArrayHelper;
use twsihan\admin\widgets\Menu as MenuWidget;
use twsihan\admin\models\mysql\Menu;
use yii\helpers\Json;

$admin = ParamsHelper::getIdentity();

?>
<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= ArrayHelper::getValue($admin, 'username') ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <?= MenuWidget::widget([
            'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
            'items' => ArrayHelper::merge(
                [
                    [
                        'label' => 'MAIN NAVIGATION',
                        'options' => ['class' => 'header'],
                    ],
                ],
                Menu::getAssignedMenu($admin->getId(), 0, function ($menu) {
                    $items = $menu['children'];
                    $return = [
                        'label' => $menu['name'],
                        'url' => [$menu['route']],
                    ];

                    // 处理我们的配置
                    $data = Json::decode($menu['data'], true);
                    if ($data) {
                        //visible
                        isset($data['visible']) && $return['visible'] = $data['visible'];
                        //icon
                        isset($data['icon']) && $data['icon'] && $return['icon'] = $data['icon'];
                        //other attribute e.g. class...
                        $return['options'] = $data;
                    }

                    // 没配置图标的显示默认图标
                    (!isset($return['icon']) || !$return['icon']) && $return['icon'] = 'circle-o';

                    $items && $return['items'] = $items;

                    if (isset($menu['route']) && !empty($menu['route'])) {
                        $return['url'] = ['/' . $menu['route']];
                    } else {
                        $return['url'] = '#';

                        (!isset($return['options']['class']) || !$return['options']['class']) && $return['options']['class'] = 'treeview';
                    }

                    return $return;
                }, true)
            ),
        ]) ?>
    </section>
</aside>
