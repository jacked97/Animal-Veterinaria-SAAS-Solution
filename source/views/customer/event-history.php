<?php
/**
 * Created by PhpStorm.
 * User: abdullahmateen87
 * Date: 11/28/16
 * Time: 8:43 PM
 */

use \yii\grid\GridView;
use \yii\widgets\Pjax;

?>


<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="header">
                <h4 class="title">Storico</h4>
            </div>


            <div class="content">
                <?php Pjax::begin(['id' => 'event-lists']); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

//                'id',
                        'date',
                        'hour_from',
                        'hour_to',
                        'company.slot',
                        [
                            'label' => 'Customer Name',
                            'value' => function ($data) {
//                        \app\components\HelperFunctions::output($data);
                                return $data->customer->lastName . ' - ' . $data->customer->firstName;
                            }
                        ],
                        [
                            'label' => 'Animale',
                            'value' => function ($data) {
                                return $data->type->title;
                            }
                        ],
                        [
                            'label' => 'Servizio erogato',
                            'value' => function ($data) {
                                return $data->activity->title;
                            }
                        ],
                        [
                            'label' => 'Veterinario',
                            'value' => function ($data) {
                                return $data->user->lastName . ' - ' . $data->user->firstName;
                            }
                        ],
                        // 'activity_id',
                        // 'description:ntext',
                        // 'user_id',
                        // 'customer_id',
                        // 'private',

//                ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>

    </div>
</div>
