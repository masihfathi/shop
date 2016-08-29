<?php

namespace app\controllers;

use Yii;
use app\components\MyController;
use yii\web\HttpException;
use app\models\Products;
use yii\helpers\Url;

class ProductsController extends MyController
{
    public function actionView($id)
    {
        $model = $this->loadModel($id);
//        Yii::$app->user->returnUrl = ['/products/view','id'=>$id];
        Yii::$app->user->returnUrl = Url::to();
        $this->view->params['breadcrumbs']=[
            ['label'=>'Categories','url'=>['/categories/index']],
            ['label'=>$model->category->name,'url'=>['/categories/view','id'=>$model->category->id]],
            $model->name,
        ];
        return $this->render('view',  compact('model'));
        
    }
    public function actionAdd($id)
    {
        $this->loadModel($id);
        $cart = Yii::$app->session->get('cart');
        if(!isset($cart[$id])){
            $cart[$id] = 1;
        }  else {
            $cart[$id]++;
        }
        Yii::$app->session->set('cart',$cart);
//        return $this->redirect(['/products/view','id'=>$id]);
        $this->goBack();        
    }
    private function loadModel($id)
    {
        if(!$model=Products::findOne($id))
        {
            throw new HttpException(404,'Product not found');
        }
        return $model;        
    } 
    public function actionSub($id)
    {
        $this->loadModel($id);
        $cart = Yii::$app->session->get('cart');
        if(isset($cart[$id])){
            $cart[$id]--;
            if ($cart[$id]<=0) {
                unset($cart[$id]);                
            }
            Yii::$app->session->set('cart', $cart);
        }
        $this->goBack();
    }
    public function actionRemove($id)
    {
        $this->loadModel($id);        
        $cart = Yii::$app->session->get('cart');
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Yii::$app->session->set('cart', $cart);            
        }
        $this->goBack();        
    }
    
    public function actionClear()
    {
        Yii::$app->session->set('cart', []); 
        $this->goHome(); 
    }
}


