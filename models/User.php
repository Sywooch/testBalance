<?php

namespace app\models;

use Yii;

use yii\db\ActiveRecord;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $nickname
 * @property integer $balance
 * @property string $auth_key
 *
 * @property Balance[] $balances
 * @property Bill[] $billsRecipient
 * @property Bill[] $billsSender
 */
class User extends ActiveRecord implements IdentityInterface
{

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['balance'], 'integer'],
            [['nickname'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'       => 'ID',
            'nickname' => 'Ник',
            'balance'  => 'Баланс',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username)
    {
        return static::findOne(['nickname' => $username]);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function getBalances()
    {
        return $this->hasMany(Balance::className(), ['user_id' => 'id']);
    }

    public function getBillsRecipient()
    {
        return $this->hasMany(Bill::className(), ['user_id_to' => 'id']);
    }

    public function getBillsSender()
    {
        return $this->hasMany(Bill::className(), ['user_id_from' => 'id']);
    }
}
