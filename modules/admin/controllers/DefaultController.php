<?php

namespace app\modules\admin\controllers;
use Yii;
use yii\filters\AccessControl;
use app\components\MyController;
use app\modules\admin\models\LoginForm;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends MyController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'user' => Yii::$app->admin,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionLogin()
    {
        if (!Yii::$app->admin->isGuest) {
        return $this->redirect(['index']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['index']);
        }
        return $this->render('login', compact('model'));
    } 
    public function actionLogout()
    {
        Yii::$app->admin->logout(FALSE);

        return $this->redirect(['index']);
    }    
}
