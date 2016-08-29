<?php
use yii\helpers\Html;
if (file_exists(Yii::$app->basePath.'/web/photos/products/'.$model->id.'.jpg')){
    $fileName = Yii::$app->homeUrl.'photos/products/'.$model->id.'.jpg';
}  else {
    $fileName = Yii::$app->homeUrl.'images/noimagefound.jpg';    
}
?>
    <div class="col-sm-3">
        <div class="alert alert-info">
            <?= Html::a(Html::img($fileName, ['class'=>'img-thumbnail','style'=>'height:200px','alt'=>$model->name]), ['/products/view','id'=>$model->id])?>
            <hr/>
            <p><strong> <?= Html::a($model->name,['/products/view','id'=>$model->id],['class'=>'btn btn-info btn-block'])?></strong></p>
            <?php if(!Yii::$app->user->isGuest && $model->quantity > 0) :?>
            <p>
            <?= Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',['/products/add','id'=>$model->id],['class'=>'btn btn-primary btn-lg pull-right'])?>
            </p>
            <?php endif;?>
            <p><strong>Quantity : </strong><span class="text-<?= ($model->quantity>0) ? 'success':'danger'?>"> <?= number_format($model->quantity)?></span></p>
            <p><strong>Price : </strong><?=number_format($model->price)?></p>
        </div>
    </div>

