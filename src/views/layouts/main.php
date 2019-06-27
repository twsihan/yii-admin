<?php

/* @var $this View */
/* @var $content string */

use yii\web\View;
use yii\widgets\Breadcrumbs;
use yii\helpers\Inflector;
use twsihan\admin\assets\AppAsset;
use twsihan\admin\widgets\Alert;
use twsihan\yii\helpers\Html;
use twsihan\admin\components\helpers\ParamsHelper;

AppAsset::register($this);

$directoryAsset = $this->getAssetManager()->getBundle('twsihan\admin\assets\AdminlteAsset')->baseUrl;
$admin = ParamsHelper::getUser();

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode(Yii::$app->name . ($this->title ? '-' . $this->title : '')) ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php $this->head() ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<?php if ($admin->isGuest) { ?>
<body class="hold-transition login-page">
<?= $this->beginBody() ?>
<?= $content ?>
<?= $this->endBody() ?>
</body>
<?php } else { ?>
<body class="hold-transition skin-blue sidebar-mini">
<?= $this->beginBody() ?>
<div class="wrapper">
    <?= $this->render('header', ['directoryAsset' => $directoryAsset]) ?>
    <?= $this->render('left', ['directoryAsset' => $directoryAsset]) ?>
    <div class="content-wrapper">
        <section class="content-header">
            <?php if (isset($this->blocks['content-header'])) { ?>
                <h1><?= $this->blocks['content-header'] ?></h1>
            <?php } else { ?>
                <h1>
                    <?php
                    if ($this->title !== null) {
                        echo Html::encode($this->title);
                    } else {
                        echo Inflector::camel2words(
                            Inflector::id2camel($this->context->module->id)
                        );
                        echo ($this->context->module->id !== Yii::$app->id) ? '<small>Module</small>' : '';
                    } ?>
                </h1>
            <?php } ?>
            <?= Breadcrumbs::widget([
                'homeLink' => [
                    'encode' => false,
                    'label' => '<i class="fa fa-dashboard"></i>首页',
                    'url' => Yii::$app->homeUrl,
                ],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </section>
        <section class="content">
            <?= Alert::widget() ?>
            <?= $content ?>
        </section>
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.4.0
        </div>
        <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
        reserved.
    </footer>
</div>
<?= $this->endBody() ?>
</body>
<?php } ?>
</html>
<?= $this->endPage() ?>
