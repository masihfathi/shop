<?php

namespace app\components;

use Yii;
use yii\web\Controller;

class MyController extends Controller
{
    /**
     * MyController extends init func of yii\web\Controller
     * Modify init function of yii\web\Controller 
     * Set cart in the session
     */
    public function init() {
        $this->layout = 'main';
        parent::init();        
        if(!Yii::$app->session->has('cart') && Yii::$app->user){
            Yii::$app->session->set('cart',[]);
        }
    }
}
