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
			$Code = md5($this -> id . date("Y-m-d H:i:s"));
			// Копирование свойств (идентификационный код, идентификатор пользователя, статус) в модель.
			$dbModel -> attributes = [
				'Code' => $Code,
				'UserID' => $this -> ID,
				'Status' => '1'
			];
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
			['Code' => $Code],
			['condition' => 'Date >= (NOW() - INTERVAL ' . $TimeInterval . ' SECOND)'],
			'UserID'
		);
	}



	/**
	 *	Поиск пользователя в БД.
	 *	Если пользователь найден, возвращает true, иначе false.
	 *
	 */
	protected function Search($ModelName, $ModelAttributes, $Condition = '', $id = 'id') {
		// Поиск пользователя в БД.
		$dbModel = $ModelName::find()
			-> where($ModelAttributes)
			-> one();
		// Если пользователь найден:
		if ($dbModel !== null) {
			// Если модель пользователя загрузилась из БД:
			if ($this -> Load($dbModel -> $id)) {
				// Инициализация идентификатора пользователя.
				$this -> id = $dbModel -> $id;
				return true;
			}
		}
		return false;
	}



	/**
	 *	Загрузка модели пользователя из базы данных.
	 *	Если модель успешно загрузилась, возвращает true.
	 *	Если модель не загрузилась, возвращает false.
	 *
	 */
	public function Load($id) {
		return parent::loadModel($id, '\app\models\User', [
			'Name', 
			'Email', 
			'Password', 
			'RegistrationDate', 
			'Enable'
		]);
	}



	/**
	 *	Сохранение модели пользователя в базу данных.
	 *	Если модель успешно сохранилась, возвращает true.
	 *	Если модель не сохранилась, возвращает false.
	 *
	 */
	public function Save() {
		return parent::saveModel('\app\models\User', [
			'Name' => $this -> Name,
			'Email' => $this -> Email,
			'Password' => $this -> Password,
			'Enable' => $this -> Enable,
		]);
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
