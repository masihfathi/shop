<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Categories;
use app\models\Users;
use app\models\Orders;
use app\models\Orderproducts;
use app\models\Products;
use app\components\MyController;
use yii\helpers\Url;
use yii\web\HttpException;
class SiteController extends MyController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','cart','pay','facture'],
                'rules' => [
                    [
                        'actions' => ['logout','cart','pay','facture'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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
//        $categories = Categories::findAll(['confirmed'=>1]);
        $categories = Categories::find()->orderBy('name')->where(['confirmed'=>1])->all();
        return $this->render('index',  compact('categories'));
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
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
        Yii::$app->user->logout(FALSE);

        return $this->goHome();
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
    /**
     * Display register page
     * 
     * @register
     */
    public function actionRegister()
    {
        $model = new Users();
        $model->scenario='register';
        $model->confirmed=1;        
        if($model->load(Yii::$app->request->post())&& $model->save()){
            $this->redirect(['/site/login']);
        }
        return $this->render('register',compact('model'));
    }
    public function actionCart()
    {
        $cart = Yii::$app->session->get('cart');
        // returnUrl be hamin safhe ast va action haii ke dar in safhe gharar darand sub, remove , clear ke dar controller products be in safhe goBack mikonand chon dar hamin safhe click mishavand
        if(empty($cart)){
            $this->goHome();
        }
        Yii::$app->user->returnUrl = Url::to();
        return $this->render('cart',  compact('cart'));
    }
    public function actionPay()
    {
        $cart = Yii::$app->session->get('cart');
        if (empty($cart)) {
            $this->goHome();
        }
        $amount = 0;
        foreach ($cart as $id => $quantity) {
            if ($product = Products::findOne($id)) {
                $amount += $product->price*$quantity;
            }
        }
        $order = new Orders();
        $order->user_id = Yii::$app->user->id;
        $order->amount = $amount;
        $order->ts = time();
        $order->confirmed = 0;
        if($order->save()){
            foreach ($cart as $id => $quantity) {
                if (Products::find()->where(['id'=>$id])->exists()) {
                    $orderProduct = new Orderproducts();
                    $orderProduct->order_id = $order->id;
                    $orderProduct->product_id = $id;
                    $orderProduct->quantity = $quantity;
                    $orderProduct->save();
                }                
            }
            Yii::$app->pay->request($amount,$order->id,date('l j F Y - H:i:s'));
        }                
    }
    public function actionFacture() {
        if (!isset($_GET['Authority'],$_GET['orderID'],$_GET['Status'])) {
            throw new HttpException(500,'Invalid Request');
        }
        $orderID = Yii::$app->request->get('orderID');
        if($model = Orders::findOne($orderID)){
            $result = Yii::$app->pay->verify($model->amount,$_GET);
            if($result['status']==1){
                Yii::$app->session->setFlash('success', 'Payment was succcessfully.');                
                    if(!$model->confirmed){
                    $model->au = $result['au'];
                    $model->confirmed = 1;
                    $model->save();
                    Yii::$app->session->set('cart', []);
                        foreach ($model->orderproducts as $orderproduct) {
                            if($product = Products::findOne($orderproduct->product_id)){
                                $product->quantity -= $orderproduct->quantity;
                                $product->save();
                            }
                        }
                    }
                return $this->render('facture',compact('model'));
            }else{
                Yii::$app->session->setFlash('failure', 'ERORR:'.$result['error']);
                $this->redirect(['/site/cart']);
            }
        }  else {
            throw new HttpException(500,'Invalid Request');
        }        
    }
}
