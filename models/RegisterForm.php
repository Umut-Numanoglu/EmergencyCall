<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RegisterForm is the model behind the registration form.
 */
class RegisterForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $confirmPassword;
    public $role;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'confirmPassword'], 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['password', 'string', 'min' => 6],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password'],
            ['role', 'default', 'value' => User::ROLE_PATIENT],
            ['role', 'in', 'range' => [User::ROLE_PATIENT, User::ROLE_RECEPTION, User::ROLE_DOCTOR]],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This username has already been taken.'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'confirmPassword' => 'Confirm Password',
            'role' => 'Role',
        ];
    }

    /**
     * Registers a new user.
     * @return bool whether the user is registered successfully
     */
    public function register()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->role = $this->role;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            
            if ($user->save()) {
                return true;
            }
        }
        return false;
    }
} 