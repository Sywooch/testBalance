<?php

namespace app\models\auth;

use Yii;
use yii\base\Model;
use app\models\User;

class SignupForm extends Model
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
            if ($user) {
                $this->addError($attribute, 'Ник занят');
            }
        }
    }

    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->nickname = $this->nickname;
            $user->generateAuthKey();
            $user->save();

            return $user;
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
