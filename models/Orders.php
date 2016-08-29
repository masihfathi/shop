<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $amount
 * @property integer $ts
 * @property string $au
 * @property integer $confirmed
 *
 * @property Orderproducts[] $orderproducts
 * @property Products[] $products
 * @property Users $user
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'amount', 'ts', 'confirmed'], 'required'],
            [['user_id', 'amount', 'ts', 'confirmed'], 'integer'],
            [['au'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'amount' => 'Amount',
            'ts' => 'Order Time',
            'au' => 'Authority',
            'confirmed' => 'Paid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderproducts()
    {
        return $this->hasMany(Orderproducts::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::className(), ['id' => 'product_id'])->viaTable('orderproducts', ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
