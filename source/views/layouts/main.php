<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\staticcontent\Navbar as CustomNavBar;

AppAsset::register($this);
$navbar = new CustomNavBar();
//\app\components\HelperFunctions::output($navbar->navbar());
$showSideBar = false;
if ((Yii::$app->controller->id != 'company' && Yii::$app->controller->action->id != "login") &&
    (Yii::$app->controller->id != 'site' && Yii::$app->controller->action->id != "login")
)
    $showSideBar = true;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrapper">
    <?php
    $company = \app\components\HelperFunctions::getCompanyData();
    $companyName = "";
    if ($company != null)
        $companyName = $company->title;
    //    NavBar::begin([
    //        'brandLabel' => $companyName,
    //        'brandUrl' => Yii::$app->homeUrl,
    //        'options' => [
    //            'class' => 'navbar-inverse navbar-fixed-top',
    //        ],
    //    ]);
    //    echo $navbar->navbar();
    //    NavBar::end();
    ?>

    <?php if ($showSideBar): ?>
        <div class="sidebar" data-background-color="white" data-active-color="danger">

            <!--
                Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black"
                Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
            -->

            <div class="sidebar-wrapper">
                <div class="logo">
                    <a href="<?= \yii\helpers\Url::to(['site/index']) ?>" class="simple-text">
                        <?= $companyName ?>
                    </a>
                </div>

                <?= $navbar->generateHTML($navbar->navbar()) ?>

            </div>

        </div>
    <?php endif; ?>
    <div class="main-panel" style="<?php if (!$showSideBar) echo "width:100%;"; ?>">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a class="navbar-brand" href="#">Gestione visite</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">


                        <?php if (!Yii::$app->user->isGuest): ?>
                            <?php if (Yii::$app->user->identity->type == \app\models\User::$company): ?>
                                <li>
                                    <a href="<?= \yii\helpers\Url::to(['company-user/settings']) ?>">
                                        <i class="ti-settings"></i>

                                        <p>Settings</p>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <li>
                                <a href="<?= \yii\helpers\Url::to(['site/logout']) ?>">
                                    <i class="ti-lock"></i>

                                    <p>Logout</p>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>

                </div>
            </div>
        </nav>
        <div class="content">
            <div class="container-fluid">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
