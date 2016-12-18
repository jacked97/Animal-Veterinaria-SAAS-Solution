<?php

namespace app\controllers;

use Yii;
use app\models\database\Calendar;
use app\models\search\Calendar as CalendarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CalendarController implements the CRUD actions for Calendar model.
 */
class CalendarController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Calendar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CalendarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Calendar model.
     * @param integer $id
     * @param integer $type_id
     * @param integer $activity_id
     * @param integer $user_id
     * @param integer $customer_id
     * @return mixed
     */
    public function actionView($id, $type_id, $activity_id, $user_id, $customer_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $type_id, $activity_id, $user_id, $customer_id),
        ]);
    }

    /**
     * Creates a new Calendar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Calendar();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'type_id' => $model->type_id, 'activity_id' => $model->activity_id, 'user_id' => $model->user_id, 'customer_id' => $model->customer_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Calendar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $type_id
     * @param integer $activity_id
     * @param integer $user_id
     * @param integer $customer_id
     * @return mixed
     */
    public function actionUpdate($id, $type_id, $activity_id, $user_id, $customer_id)
    {
        $model = $this->findModel($id, $type_id, $activity_id, $user_id, $customer_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'type_id' => $model->type_id, 'activity_id' => $model->activity_id, 'user_id' => $model->user_id, 'customer_id' => $model->customer_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Calendar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $type_id
     * @param integer $activity_id
     * @param integer $user_id
     * @param integer $customer_id
     * @return mixed
     */
    public function actionDelete($id, $type_id, $activity_id, $user_id, $customer_id)
    {
        $this->findModel($id, $type_id, $activity_id, $user_id, $customer_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Calendar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param integer $type_id
     * @param integer $activity_id
     * @param integer $user_id
     * @param integer $customer_id
     * @return Calendar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $type_id, $activity_id, $user_id, $customer_id)
    {
        if (($model = Calendar::findOne(['id' => $id, 'type_id' => $type_id, 'activity_id' => $activity_id, 'user_id' => $user_id, 'customer_id' => $customer_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
