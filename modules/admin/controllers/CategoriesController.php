<?php

namespace app\modules\admin\controllers;

use Yii;
use app\components\MyController;
use app\models\Categories;
use yii\filters\AccessControl;
use yii\data\Pagination;

class CategoriesController extends MyController {
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'user' => Yii::$app->admin,
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $query = Categories::find();
        $pagination = new Pagination([
            'defaultPageSize' => 2,
            'totalCount' => $query -> count(),
        ]); 
        $models = $query -> limit( $pagination -> limit )-> offset( $pagination -> offset )-> all();
        return $this->render('index',  compact('models','pagination'));       
    }
}
