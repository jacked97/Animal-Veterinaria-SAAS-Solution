<?php

namespace app\controllers;

use app\components\HelperFunctions;
use app\config\KeyConfig;
use app\models\custom\CustomerRegistration;
use app\models\database\Company;
use app\models\database\Customer;
use app\models\LoginForm;
use app\models\User;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use Yii;

class CompanyController extends \yii\web\Controller
{
    public $defaultAction = 'index';
    private $companyData;
    public static $companyId = null;

    public function actionIndex($id)
    {
        return $this->redirect(["company/$id/login", 'type' => User::$customer]);
        $this->findCompany($id);
//        HelperFunctions::output($company);
        return $this->render('index', [
            'company' => $this->companyData,
        ]);
    }


    public function actionRegistration($id)
    {
        $this->findCompany($id);
        $registrationForm = new CustomerRegistration();
        $registrationForm->companyData = $this->companyData;

        if ($registrationForm->load(\Yii::$app->request->post()) && $registrationForm->validate()) {

            $storeUserRegistration = new Customer();
            $storeUserRegistration->load(array('Customer' => \Yii::$app->request->post()['CustomerRegistration']));
            $storeUserRegistration->timestamp = HelperFunctions::currMysqlDateTime();
            $storeUserRegistration->company_id = $this->companyData->id;
            if ($storeUserRegistration->save()) {
                \Yii::$app->session->addFlash('success', 'Registration Completed, <a href="' .
                    Url::to(["company/$id/login", 'type' => User::$customer]) . '">Click here</a> to login');
                return $this->redirect(['registration', 'id' => $id]);
            }
        }


        return $this->render('registration', [
            'registrationForm' => $registrationForm
        ]);
    }

    public function actionLogin($id, $type = null)
    {
        $this->findCompany($id);

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        User::$typeS = User::$customer;

        if ($type != null) {
            User::$typeS = $type;
            User::$companyId = $this->companyData->id;
        }

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $_SESSION[KeyConfig::$userTypeSessionKey] = User::$typeS;
            $_SESSION[KeyConfig::$userCompanyIdKey] = User::$companyId;
            return $this->goBack();
        }
        return $this->render('/site/login', [
            'model' => $model,
            'fromCompanyController' => true
        ]);
    }

    private function findCompany($id)
    {
        $this->companyData = Company::findOne([
            'urlCode' => $id
        ]);
        self::$companyId = $id;
        if ($this->companyData == null)
            throw new NotFoundHttpException("Unable to recognise the company details.");

    }


}
