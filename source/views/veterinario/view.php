<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\database\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Veterinario', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="header">
                <h4 class="title"><?= Html::encode($this->title) ?></h4>
            </div>


            <div class="content">
                <p>
                    <?= Html::a('Update', ['update', 'id' => $model->id, 'company_id' => $model->company_id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $model->id, 'company_id' => $model->company_id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'firstName',
                        'lastName',
                        'email:email',
                        'timestamp',
                        'mobile',
                        'status',
                        'image',
                        'slot',
                        'company_id',
                    ],
                ]) ?>

            </div>
        </div>
    </div>
</div>
