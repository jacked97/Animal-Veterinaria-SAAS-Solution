<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\database\Calendar */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Calendars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calendar-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id, 'type_id' => $model->type_id, 'activity_id' => $model->activity_id, 'user_id' => $model->user_id, 'customer_id' => $model->customer_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id, 'type_id' => $model->type_id, 'activity_id' => $model->activity_id, 'user_id' => $model->user_id, 'customer_id' => $model->customer_id], [
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
            'date',
            'hour_from',
            'hour_to',
            'type_id',
            'activity_id',
            'description:ntext',
            'user_id',
            'customer_id',
            'private',
        ],
    ]) ?>

</div>
