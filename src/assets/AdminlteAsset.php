<?php

namespace twsihan\admin\assets;

use yii\base\Exception;
use yii\web\AssetBundle;

/**
 * Class AdminlteAsset
 *
 * @package twsihan\admin\assets
 * @author twsihan <twsihan@gmail.com>
 */
class AdminlteAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/dist';
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];
    public $css = [
        'css/AdminLTE.css',
    ];
    public $js = [
        'js/adminlte.min.js'
    ];

    public $skin = '_all-skins';


    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->skin) {
            if (('_all-skins' !== $this->skin) && (strpos($this->skin, 'skin-') !== 0)) {
                throw new Exception('Invalid skin specified');
            }

            $this->css[] = sprintf('css/skins/%s.min.css', $this->skin);
        }

        parent::init();
    }
}
