<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\database\Calendar */

$this->title = 'Update Calendar: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Calendars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id, 'type_id' => $model->type_id, 'activity_id' => $model->activity_id, 'user_id' => $model->user_id, 'customer_id' => $model->customer_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="calendar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
