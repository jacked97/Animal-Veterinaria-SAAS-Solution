<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Veterinario';
$this->params['breadcrumbs'][] = $this->title;
?>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/6.1.1/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/sweetalert2/6.1.1/sweetalert2.min.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="header">
                <h4 class="title"><?= Html::encode($this->title) ?></h4>
            </div>

            <div class="content">
                <p>
                    <?= Html::a('Create Veterinario', ['create'], ['class' => 'btn btn-success']) ?>
                </p>

                <div style="overflow-x: scroll;">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

//                        'id',
                            'firstName',
                            'lastName',
                            'email:email',
                            'timestamp',
                            // 'mobile',
                            // 'status',
                            // 'image',
                            // 'slot',
                            // 'company_id',
                            [
                                'attribute' => 'View',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return "<a href='" . Url::to(['view', 'id' => $model->id, 'company_id' => $model->company_id]) . "'>" .
                                    "<img style='height:32px;' src='" . Yii::getAlias('@web') . "/icons/view_icon.png'/>" .
                                    "</a>";
                                }
                            ],
                            [
                                'attribute' => 'Edit',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return "<a href='" . Url::to(['update', 'id' => $model->id, 'company_id' => $model->company_id]) . "'>" .
                                    "<img style='height:32px;' src='" . Yii::getAlias('@web') . "/icons/edit_icon.png'/>" .
                                    "</a>";
                                }
                            ],
                            [
                                'attribute' => 'Delete',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return "<a onClick='deleteVet(\"" . Url::to(['delete', 'id' => $model->id, 'company_id' => $model->company_id], true) . "\")'>" .
                                    "<img style='height:32px;' src='" . Yii::getAlias('@web') . "/icons/delete_icon.png'/>" .
                                    "</a>";
                                }
                            ],

//                        ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function deleteVet(url) {
        console.log("asdasda");
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {
            window.location.href = url;
        })
    }
</script>
