<?php

namespace app\models\auth;

use Yii;
use yii\base\Model;
use app\models\User;

class LoginForm extends Model
{
    public $nickname;
    public $rememberMe = true;

    private $_user = false;

    public function rules()
    {
        return [

            // REQUIRED
            [
                [
                    'nickname'
                ],
                'required',
                'message' => 'Введите ник'
            ],

            // BOOLEAN
            ['rememberMe', 'boolean'],

            // VALIDATE NICKNAME
            ['nickname', 'validateNickname'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nickname'    => 'Ник',
            'rememberMe'  => 'Запомнить меня',
        ];
    }

    public function validateNickname($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, 'Пользователь не найден');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }

        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->nickname);
        }

        return $this->_user;
    }
}
