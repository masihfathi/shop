<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;
use app\models\Products;
$this->title = 'Cart';
?>
<?php if(Yii::$app->session->hasFlash('failure')):?>
    <?= Alert::widget(['body'=>  Yii::$app->session->getFlash('failure'),'options'=>['class'=>'alert-warning'],'closeButton'=>['<span class="glyphicon glyphicon-remove"></span>']])?>
<?php endif;?>
<table class="table table-bordered table-condensed table-hover table-striped">
    <thead>
        <tr class="info">
            <th class="text-center" style="width: 50px;">ID</th>
            <th class="text-center">Product Name</th>
            <th class="text-center" style="width: 100px;">Unit Price</th>
            <th class="text-center" style="width: 100px;">Quantity</th>
            <th class="text-center" style="width: 100px;">Sum</th>
            <th class="text-center" style="width: 75px;"><span class="glyphicon glyphicon-cog"></span></th>
        </tr>
    </thead>
    <tbody>
        <?php $i=1;?>
        <?php $total = 0;?>
        <?php foreach ($cart as $id=>$quantity):?>
        <?php if($model = Products::findOne($id)):?>
        <tr>
            <td class="text-center"><?= $i++?></td>
            <td><?= Html::a($model->name,['/products/view','id'=>$id],['target'=>'_blank'])?></td>
            <td class="text-center"><?= number_format($model->price)?></td>
            <td class="text-center"><?= $quantity?></td>
            <?php $sum = $model->price*$quantity;?>
            <?php $total+=$sum;?>
            <td class="text-center"><?= number_format($sum)?></td>
            <td class="text-center">
                <?= Html::a('<span class="glyphicon glyphicon-minus-sign"></span>', ['/products/sub','id'=>$id], ['class'=>'btn btn-warning btn-xs'])?>
                <?= Html::a('<span class="glyphicon glyphicon-remove-sign"></span>', ['/products/remove','id'=>$id], ['class'=>'btn btn-danger btn-xs'])?>
            </td>
        </tr>
        <?php endif;?>
        <?php endforeach;?>
    </tbody>
    <tfoot>
        <tr class="success">
            <td class="text-right" colspan="4"><strong>Total: </strong></td>
            <td class="text-center"><?=number_format($total)?></td>
            <td class="text-center"><?=  Html::a('<span class="glyphicon glyphicon-remove-circle"></span>', ['/products/clear'], ['class'=>'btn btn-danger btn-xs btn-block','onclick'=>'return confirm("Are You Sure?")'])?></td>
        </tr>
    </tfoot>
    
</table>
<p class="text-center">
    <?=Html::a('<span class="glyphicon glyphicon-usd"></span> Online Payment', ['/site/pay'], ['class'=>'btn btn-primary btn-large'])?>
</p>