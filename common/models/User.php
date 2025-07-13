<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $role
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    const ROLE_PATIENT = 'patient';
    const ROLE_RECEPTION = 'reception';
    const ROLE_DOCTOR = 'doctor';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password_hash', 'role', 'first_name', 'last_name'], 'required'],
            [['username', 'email'], 'unique'],
            [['email'], 'email'],
            [['role'], 'in', 'range' => [self::ROLE_PATIENT, self::ROLE_RECEPTION, self::ROLE_DOCTOR]],
            [['phone'], 'string', 'max' => 20],
            [['first_name', 'last_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password_hash' => 'Password Hash',
            'role' => 'Role',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Gets the full name of the user
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Checks if user is a patient
     *
     * @return bool
     */
    public function isPatient()
    {
        return $this->role === self::ROLE_PATIENT;
    }

    /**
     * Checks if user is a receptionist
     *
     * @return bool
     */
    public function isReception()
    {
        return $this->role === self::ROLE_RECEPTION;
    }

    /**
     * Checks if user is a doctor
     *
     * @return bool
     */
    public function isDoctor()
    {
        return $this->role === self::ROLE_DOCTOR;
    }
} 