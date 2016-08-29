<?php
use yii\helpers\Html;
?>
<div class="row">
    <?php    foreach ($categories as $category) : ?>
        <div class="col-sm-2">
            <?= Html::a($category->name, ['/categories/view','id'=>$category->id], ['class'=> 'btn btn-info btn xs btn-block']) ?>
        </div>
    <?php    endforeach;?>
</div>    
<div class="site-index">

    <div class="jumbotron">
        <div class="well">
            <h1><?= Yii::$app->name ?></h1>          
            <p class="lead">please use above links to access site features.</p>
        </div>
    </div>


</div>
