<?php

namespace app\controllers;

use Yii;
use app\components\MyController;
use app\models\Categories;
use app\models\Products;
use yii\web\HttpException;
use yii\helpers\Url;

class CategoriesController extends MyController
{
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        // Url::to() be hamin action return kon
        Yii::$app->user->returnUrl = Url::to();
        $products = $this->loadProducts($id);
        $this->view->params['breadcrumbs'] = [
            ['label'=>'Categories','url'=>['/categories/index']],
            $model->name,
        ]; 
        return $this->render('view',  compact('model','products'));        
    }
    public function actionIndex()
    {
        $this->view->params['breadcrumbs']=['categories'];
        $models = Categories::findAll(['confirmed'=>1]);
        return $this->render('index',  compact('models'));        
    }
    private function loadModel($id)
    {           
     if(!$model = Categories::findOne($id))
     {
         throw new HttpException(404,'Category not found');
     }
     return $model;
    }
    private function loadProducts($id)
     {
        $products = Products::find()->where(['category_id'=>$id,'confirmed'=>1])->orderBy('name')->all();
        return $products;
     }            
}
