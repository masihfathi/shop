<?php

use yii\helpers\Html;
use app\models\Products;
$this->title = 'categories';
?>
<?php if(!$models):?>
<div class="alert alert-danger">
    <p>There are no category defined here.</p>
</div>
<?php else: ?>
<ul class="list-group">
    <?php    foreach ($models as $model) :?>
    <li class="list-group-item">
        <span class="label label-danger"><?= Products::find()->where(['category_id'=>$model->id,'confirmed'=>1])->count()?></span>
        <?= Html::a($model->name, ['/categories/view','id'=>$model->id])?>
    </li>
    <?php    endforeach;?>
</ul>
<?php endif; ?>


