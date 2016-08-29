<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $fullname
 * @property string $address
 * @property string $email
 * @property string $mobile
 * @property string $postcode
 * @property string $user
 * @property string $pass
 * @property integer $confirmed
 *
 * @property Orders[] $orders
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $authKey;
    public $pass_repeat;
    private $_oldPass;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fullname', 'address', 'email', 'mobile', 'postcode', 'user', 'pass', 'confirmed'], 'required'],
            [['address'], 'string'],
            [['confirmed'], 'integer'],
            ['email','email'],
            ['postcode','match','pattern'=>'#^[13-9]{10}$#'],
            ['mobile','match','pattern'=>'#^0?9[0-3][0-9]{8}$#'],
            [['fullname', 'email', 'user', 'pass'], 'string', 'max' => 255],
            ['postcode', 'string', 'max' => 10],
            ['mobile', 'string', 'max' => 11],
            ['pass_repeat','required','on'=>'register'],
            ['pass_repeat','compare','compareAttribute'=>'pass','message'=>'Password and Confirm must be the same','on'=>'register'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fullname' => 'Full Name',
            'address' => 'Address',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'postcode' => 'Post Code',
            'user' => 'Username',
            'pass' => 'Password',
            'confirmed' => 'Confirmed',
            'pass_repeat' => 'Confirm Password',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['user_id' => 'id']);
    }
    public function beforeSave($insert) {
        if(trim($this->pass)!=='' || $this->pass != $this->_oldPass){
        $this->pass = Yii::$app->security->generatePasswordHash($this->pass);
        $this->_oldPass = $this->pass;
        }
        return parent::beforeSave($insert);
    }
    public function afterFind()
    {
        parent::afterFind();        
        $this->_oldPass = $this->pass;
    }
    public static function findIdentity($id)
    {
        $model = self::findOne($id);
        return $model ? new static($model) : null;
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }
    public function getId()
    {
        return $this->id;        
    }
    public function getAuthKey()
    {
        return $this->authKey;        
    }    
    public function validateAuthKey($authKey)
    {
        return $this->authKey===$authKey;        
    }    
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->pass);        
    }
    

}
