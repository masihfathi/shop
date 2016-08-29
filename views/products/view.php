<?php

use yii\helpers\Html;
if (file_exists(Yii::$app->basePath.'/web/photos/products/'.$model->id.'.jpg')){
    $fileName = Yii::$app->homeUrl.'photos/products/'.$model->id.'.jpg';
}  else {
    $fileName = Yii::$app->homeUrl.'images/noimagefound.jpg';    
}
$cart = Yii::$app->session->get('cart');
?>
<div class="row">
    <div class="col-sm-4">
    <?= Html::a(Html::img($fileName, ['class'=>'img-thumbnail','style'=>'height:200px','alt'=>$model->name]), ['/products/view','id'=>$model->id])?>        
    </div>
    <div class="col-sm-8">
        <h1><?= Html::encode($model->name)?></h1>
        <h4 class="text-muted"><?= Html::encode($model->category->name)?></h4>
        <hr />
        <p><strong>Quantity : </strong><span class="text-<?= ($model->quantity>0) ? 'success':'danger'?>"> <?= number_format($model->quantity)?></span></p>
        <p><strong>Price : </strong><?=number_format($model->price)?></p>
       <?php if(!Yii::$app->user->isGuest && $model->quantity > 0) :?>
            <?= Html::a('<span class="glyphicon glyphicon-plus-sign"></span> Add to cart',['/products/add','id'=>$model->id],['class'=>'btn btn-primary btn-lg'])?>
       <?php endif;?>
    </div>
</div>

