<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Register form
 */
class RegisterForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $confirmPassword;
    public $first_name;
    public $last_name;
    public $phone;
    public $role;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'confirmPassword', 'first_name', 'last_name', 'role'], 'required'],
            [['username', 'email'], 'string', 'max' => 255],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'email'],
            [['username', 'email'], 'unique', 'targetClass' => User::class],
            [['role'], 'in', 'range' => [User::ROLE_PATIENT, User::ROLE_RECEPTION, User::ROLE_DOCTOR]],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords do not match.'],
            [['password'], 'string', 'min' => 6],
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
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone',
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
            $user->first_name = $this->first_name;
            $user->last_name = $this->last_name;
            $user->phone = $this->phone;
            $user->role = $this->role;
            $user->setPassword($this->password);
            
            return $user->save();
        }
        return false;
    }
} 