<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admins".
 *
 * @property integer $id
 * @property string $fullname
 * @property string $user
 * @property string $pass
 * @property integer $confirmed
 */
class Admins extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $authKey;
    public $pass_repeat;
    private $_oldPass;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admins';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fullname', 'user', 'pass', 'confirmed'], 'required'],
            [['confirmed'], 'integer'],
            [['fullname', 'user', 'pass'], 'string', 'max' => 255],
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
            'user' => 'Username',
            'pass' => 'Password',
            'pass_repeat' => 'Confirm Password',            
            'confirmed' => 'Confirmed',
        ];
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
