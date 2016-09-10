<?php

namespace app\models\models;

use app\models\User as UserDB;
use app\models\Recovery as RecoveryDB;
use yii\web\IdentityInterface;

/**
 * User class file.
 *
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 * @copyright 2016 Konstantin Poluektov
 *
 */

 
 
/**
 * User manages the user.
 *
 * @property string $Name The user's name.
 * @property string $Email The user's E-mail.
 * @property string $Password The user's password.
 * @property date $RegistrationDate The date of registration.
 * @property boolean $Enable The user's state.
 * 
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 *
 */
class User extends LSD {

	/**
	 *	Временной интервал активности вызова (секунды)
	 *
	 */
	const RECOVERY_TIME_INTERVAL = 3600;



	/**
	 *	Имя пользователя.
	 *
	 */
	protected $Name;

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
	 *	Дата регистрации.
	 *
	 */
	protected $RegistrationDate;

	/**
	 *	Cостояние.
	 *
	 */
	protected $Enable;



	/**
	 *	Устанавливается имя, электронную почту, пароль и состояние пользователя.
	 *
	 */
	function __construct($Name = null, $Email = null, $Password = null, $Enable = null) {
		$this -> Name = $Name;
		$this -> Email = $Email;
		$this -> Password = $Password;
		$this -> Enable = $Enable;
	}



	// public static function tableName()
 //    {
 //        return 'user';
 //    }

	// /**
 //     * Finds an identity by the given ID.
 //     *
 //     * @param string|integer $id the ID to be looked for
 //     * @return IdentityInterface|null the identity object that matches the given ID.
 //     */
 //    public static function findIdentity($id)
 //    {
 //        return static::findOne($id);
 //    }

 //    /**
 //     * Finds an identity by the given token.
 //     *
 //     * @param string $token the token to be looked for
 //     * @return IdentityInterface|null the identity object that matches the given token.
 //     */
 //    public static function findIdentityByAccessToken($token, $type = null)
 //    {
 //        return static::findOne(['access_token' => $token]);
 //    }

 //    /**
 //     * @return int|string current user ID
 //     */
 //    public function getId()
 //    {
 //        return $this->id;
 //    }

 //    /**
 //     * @return string current user auth key
 //     */
 //    public function getAuthKey()
 //    {
 //        return $this->auth_key;
 //    }

 //    /**
 //     * @param string $authKey
 //     * @return boolean if auth key is valid for current user
 //     */
 //    public function validateAuthKey($authKey)
 //    {
 //        return $this->getAuthKey() === $authKey;
 //    }



	/**
	 *	Возвращает имя пользователя.
	 *
	 */
	public function getName() {
		return $this -> Name;
	}



	/**
	 *	Возвращает электронную почту пользователя.
	 *
	 */
	public function getEmail() {
		return $this -> Email;
	}



	/**
	 *	Обновление имени пользователя.
	 *
	 */
	public function setName($Name) {
		$this -> Name = $Name;
	}



	/**
	 *	Обновление электронной почты пользователя.
	 *
	 */
	public function setEmail($Email) {
		$this -> Email = $Email;
	}



	/**
	 *	Регистрация запроса на восстановление доступа.
	 *
	 */
	public function setPasswordRecovery() {
		// Создание модели восстановления доступа.
		$dbModel = new RecoveryDB;
		// Если модель создана:
		if ($dbModel !== null) {
			// Генерация идентификационного кода для ссылки.
			$Code = md5($this -> ID . date("Y-m-d H:i:s"));
			// Копирование свойств (идентификационный код, идентификатор пользователя, статус) в модель.
			$dbModel -> attributes = array(
				'Code' => $Code,
				'UserID' => $this -> ID,
				'Status' => '1'
			);
			// Сохранение модели в БД.
			if ($dbModel -> save())
				return $Code;
		}
		return false;
	}



	/**
	 *	Поиск пользователя в БД по указанной электронной почте.
	 *	Если пользователь найден, возвращает true, иначе false.
	 *
	 */
	public function SearchByEmail($Email) {
		return $this -> Search('\app\models\User', ['Email' => $Email]);
	}



	/**
	 *	Поиск пользователя в БД по указанному идентификационному коду для восстановления доступа.
	 *	Если пользователь найден, возвращает true, иначе false.
	 *
	 */
	public function SearchByRecoveryCode($Code, $TimeInterval = self::RECOVERY_TIME_INTERVAL) {
		return $this -> Search(
			'\app\models\Recovery',
			array('Code' => $Code),
			array('condition' => 'Date >= (NOW() - INTERVAL ' . $TimeInterval . ' SECOND)'),
			'UserID'
		);
	}



	/**
	 *	Поиск пользователя в БД.
	 *	Если пользователь найден, возвращает true, иначе false.
	 *
	 */
	protected function Search($ModelName, $ModelAttributes, $Condition = '', $ID = 'ID') {
		// Поиск пользователя в БД.
		//$dbModel = $ModelName::model() -> findByAttributes($ModelAttributes, $Condition);
		$dbModel = $ModelName::find()
			-> where($ModelAttributes)
			-> one();
		// Если пользователь найден:
		if ($dbModel !== null) {
			// Если модель пользователя загрузилась из БД:
			if ($this -> Load($dbModel -> $ID)) {
				// Инициализация идентификатора пользователя.
				$this -> ID = $dbModel -> $ID;
				return true;
			}
		}
		return false;
		// return true;
	}



	/**
	 *	Загрузка модели пользователя из базы данных.
	 *	Если модель успешно загрузилась, возвращает true.
	 *	Если модель не загрузилась, возвращает false.
	 *
	 */
	public function Load($ID) {
		return parent::loadModel($ID, '\app\models\User', array(
			'Name', 
			'Email', 
			'Password', 
			'RegistrationDate', 
			'Enable'
		));
	}



	/**
	 *	Сохранение модели пользователя в базу данных.
	 *	Если модель успешно сохранилась, возвращает true.
	 *	Если модель не сохранилась, возвращает false.
	 *
	 */
	public function Save() {
		return parent::saveModel('\app\models\User', array(
			'Name' => $this -> Name,
			'Email' => $this -> Email,
			'Password' => $this -> Password,
			'Enable' => $this -> Enable,
		));
	}



	/**
	 *	Удаление модели пользователя из базы данных.
	 *	Если модель успешно удалилась, возвращает true.
	 *	Если модель не удалилась, возвращает false.
	 *
	 */
	public function Delete() {
		return parent::deleteModel('\app\models\User');
	}

}
