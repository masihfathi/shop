<?php

use yii\helpers\Html;
$this->title = Html::encode($model->name);
?>
<h1><?= Html::encode($model->name)?></h1>
<hr />
<?php if (!$products): ?>
<div class="alert alert-warning">
    <p>Currently, this category has no products </p>
</div>
<?php else: ?>
<div class="row">
    <?php    foreach ($products as $product) : ?>
        <?= $this->render('/products/_view',  ['model'=>$product]) ?>
    <?php    endforeach; ?>   
</div>
<?php endif; ?>
