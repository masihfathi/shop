<?php

use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
$this->title = 'Admin - Categories';
?>
<?php if(!$models): ?>
<?= Alert::widget(['body'=>'No Category Found!','options'=>['class'=>'alert-danger']])?>
<?php else:?>
<?php Pjax::begin();?>
<?= LinkPager::widget(['pagination'=>$pagination]) ?>
<table class="table table-bordered table-condensed table-hover table-striped ">
    <thead>
        <tr class="info">
            <th>ID</th>
            <th>Name</th>
            <th>Confirmed</th>
        </tr>
    </thead>
    <tbody>
        <?php $i=1;?>
        <?php foreach ($models as $model) :?>
        <tr> 
            <td><?= $i ?></td>
            <td><?= Html::encode($model->name) ?></td>
            <td><span class="glyphicon glyphicon-<?= ($model->confirmed ? 'ok text-success' : 'remove text-danger')?>"></span></td>
            <?php $i++;?>
        <?php endforeach;?>
    </tbody>
    
</table>
<?php Pjax::end();?>
<?php endif; ?>

