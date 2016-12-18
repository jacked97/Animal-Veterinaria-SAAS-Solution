<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\database\User */

$this->title = 'Create Veterinario';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="header">
                <h4 class="title"><?= Html::encode($this->title) ?></h4>
            </div>

            <div class="content">

                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>

        </div>
    </div>
</div>