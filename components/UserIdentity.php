<?php

namespace app\components;

use Yii;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use app\models\User as tableUser;



/**
 *	Контроллер идентификации пользователя.
 *	Выполняет авторизацию пользователя. Сообщает об ошибках при авторизации.
 *	Предоставляет идентификатор авторизованного пользователя.
 *
 */
class UserIdentity extends ActiveRecord implements IdentityInterface {

	const ERROR_NONE = 0;
	const ERROR_EMAIL_INVALID = 1;
	const ERROR_PASSWORD_INVALID = 2;

	public $errorCode = '';

	public $errorMessage = '';

	/**
	 *	Электронная почта.
	 *
	 */
	protected $Email;

	/**
	 *	Пароль.
	 *
	 */
	protected $Password;

	/**
	 *	Устанавливается имя, электронную почту, пароль и состояние пользователя.
	 *
	 */
	function __construct($Email = null, $Password = null) {
		$this -> Email = $Email;
		$this -> Password = $Password;
	}

	/**
	 *	Идентификатор авторизованного пользователя.
	 *
	 */
	// public $id;



	/**
	 *	Выполняет авторизацию пользователя.
	 *	Ищет в БД пользователя с указанной электронной почтой.
	 *
	 *		Если пользователь найден, проверяет правильность указанного пароля.
	 *
	 *			Если пароль правильный, устанавливается идентификатор пользователя, 
	 *			сбрасывается код и описание ошибки.
	 *			Возвращает 1.
	 *
	 *			Если пароль не правильный, устанавливается код ошибки ERROR_USERNAME_INVALID.
	 *			Возвращает 0.
	 *
	 *		Если пользователь не найден, устанавливается код ошибки ERROR_USERNAME_INVALID.
	 *		Возвращает 0.
	 *
	 */
	public function authenticate() {
		// Поиск в БД пользователя с указанной электронной почтой.
		$Model = tableUser::findOne(['Email' => $this -> Email]);
		// Если пользователь не найден в БД:
		if ($Model === null) {
			// Устанавливается код и описание ошибки.
			$this -> errorCode = self::ERROR_EMAIL_INVALID;
			$this -> errorMessage = Yii::t('Dictionary', 'Incorrect e-mail address or password');
		}
		// Если пользователь найден, но полученный пароль не соответствует:
		else if (!Yii::$app -> getSecurity() -> validatePassword($this -> Password, $Model -> Password)) {
			// Устанавливается код и описание ошибки.
			$this -> errorCode = self::ERROR_EMAIL_INVALID;
			$this -> errorMessage = Yii::t('Dictionary', 'Incorrect e-mail address or password');
		}
		// Если пользователь найден и пароль верный:
		else {
			// Устанавливается идентификатор пользователя.
			$this -> id = $Model -> id;
			// Сброс кода и описания ошибки.
			$this -> errorCode = self::ERROR_NONE;
			$this -> errorMessage = '';
			return true;
		}
		return !$this -> errorCode;
	}



	/**
	 *	Возвращает идентификатор авторизованного пользователя.
	 *
	 */
	// public function getId() {
	// 	return $this -> ID;
	// }



	public static function tableName() {
        return 'user';
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId() {
        return $this -> id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey() {
        return $this -> auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey) {
        return $this -> getAuthKey() === $authKey;
    }

}
