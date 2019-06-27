<?php

namespace twsihan\admin\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 *
 * @package twsihan\admin\assets
 * @author twsihan <twsihan@gmail.com>
 */
class AppAsset extends AssetBundle
{
    public $depends = [
        'twsihan\admin\assets\AdminlteAsset',
    ];
}
