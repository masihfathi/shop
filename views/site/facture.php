<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;
use app\models\Products;
$this->title = 'Facture';
?>
<?php if(Yii::$app->session->hasFlash('success')):?>
    <?= Alert::widget(['body'=>  Yii::$app->session->getFlash('success'),'options'=>['class'=>'alert-success']])?>
<?php endif;?>
<table class="table table-bordered table-condensed table-hover table-striped">
    <thead>
        <tr class="info">
            <th class="text-center" style="width: 50px;">ID</th>
            <th class="text-center">Product Name</th>
            <th class="text-center" style="width: 100px;">Unit Price</th>
            <th class="text-center" style="width: 100px;">Quantity</th>
            <th class="text-center" style="width: 100px;">Sum</th>
        </tr>
    </thead>
    <tbody>
        <?php $i=1;?>
        <?php $total = 0;?>
        <?php foreach ($model->orderproducts as $orderproduct):?>
        <?php if($product = Products::findOne($orderproduct->product_id)):?>
        <tr>
            <td class="text-center"><?= $i++?></td>
            <td><?= Html::a($product->name,['/products/view','id'=>$product->id],['target'=>'_blank'])?></td>
            <td class="text-center"><?= number_format($product->price)?></td>
            <td class="text-center"><?= $orderproduct->quantity?></td>
            <?php $sum = $product->price*$orderproduct->quantity;?>
            <?php $total+=$sum;?>
            <td class="text-center"><?= number_format($sum)?></td>
        </tr>
        <?php endif;?>
        <?php endforeach;?>
    </tbody>
    <tfoot>
        <tr class="success">
            <td class="text-right" colspan="4"><strong>Total: </strong></td>
            <td class="text-center"><?=number_format($total)?></td>
        </tr>
    </tfoot>
    
</table>
<h4><strong>Payment Time: <?=date('l j F Y - H:i:s',$model->ts)?></strong></h4>