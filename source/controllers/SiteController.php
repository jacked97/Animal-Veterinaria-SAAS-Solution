<?php

namespace app\controllers;

use app\components\HelperFunctions;
use app\config\KeyConfig;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->type == User::$customer)
                return $this->redirect(['customer/new-appointment']);
            else
                $this->redirect(['veterinario/index']);
        } else
            return $this->redirect(['site/login', 'type' => 'company']);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin($type)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        User::$typeS = $type;
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $_SESSION[KeyConfig::$userTypeSessionKey] = User::$typeS;
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        $companyData = HelperFunctions::getCompanyData();
        $isCompany = false;
        if (Yii::$app->user->identity->type == User::$company)
            $isCompany = true;

        Yii::$app->user->logout();
        if ($isCompany)
            return $this->goHome();
        else
            return $this->redirect(["company/$companyData->urlCode"]);
//

    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
