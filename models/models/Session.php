<?php

namespace app\models\models;



use Yii;
use app\models\Session as tableSession;



/**
 * Session class file.
 *
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 * @copyright 2016 Konstantin Poluektov
 *
 */

 
 
/**
 * Session manages the player sessions.
 *
 * 
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 *
 */
class Session extends LSD {

	/**
	 *	Тип устройства: Компьютер
	 *
	 */
	const DESKTOP_DEVICE = 1;

	/**
	 *	Тип устройства: Планшет
	 *
	 */
	const TABLET_DEVICE = 2;

	/**
	 *	Тип устройства: Смартфон
	 *
	 */
	const PHONE_DEVICE = 3;



	/**
	 *	Идентификатор пользователя.
	 *
	 */
	protected $UserID;

	/**
	 *	Дата и время начала сессии.
	 *
	 */
	protected $StartDate;

	/**
	 *	Дата и время окончания сессии.
	 *
	 */
	protected $EndDate;



	/**
	 *
	 *
	 */
	function __construct($UserID = null) {
		//
		$this -> UserID = $UserID;
	}



	/**
	 *	Начинает новую сессию.
	 *
	 */
	public function Start() {
		$this -> Save();
	}



	/**
	 *	Продолжает сессию.
	 *	Фиксирует текущее время сессии.
	 *
	 */
	public function Update() {
		$this -> EndDate = date("Y-m-d H:i:s");
		$this -> Save();
	}



	/**
	 *	Завершает сессию.
	 *	Устанавливает время окончания сессии.
	 *
	 */
	public function Stop() {
		$this -> EndDate = date("Y-m-d H:i:s");
		$this -> Save();
	}



	/**
	 *	Загружает сессию по указанному идентификатору пользователя $UserID.
	 *	Если сессию успешно загружена, возвращает true.
	 *	Если сессию не загружена, возвращает false.
	 *
	 */
	public function Search($UserID) {
		// Поиск сессии в БД по указанному идентификатору пользователя.
		$dbModel = tableSession::find()
			-> where(['UserID' => $UserID])
			-> orderBy('id DESC')
			-> one();
		// Если сессия найдена:
		if ($dbModel !== null) {
			// Если модель загрузилась:
			if ($this -> Load($dbModel -> id)) {
				// Инициализация идентификатора сессии.
				$this -> id = $dbModel -> id;
				return true;
			}
		}
		return false;
	}



	/**
	 *	Загрузка модели сессии из базы данных.
	 *	Если модель успешно загрузилась, возвращает true.
	 *	Если модель не загрузилась, возвращает false.
	 *
	 */
	public function Load($id) {
		return parent::loadModel($id, '\app\models\Session', [
			'UserIP',
			'UserAgent',
			'DeviceType',
			'StartDate',
			'EndDate',
			'UserID'
		]);
	}



	/**
	 *	Сохранение модели сессии в базу данных.
	 *	Если модель успешно сохранилась, возвращает true.
	 *	Если модель не сохранилась, возвращает false.
	 *
	 */
	public function Save() {
		//
		if (!$this -> UserID)
			return false;
		//
		if (Yii::$app -> mobileDetect -> isPhone())
			$deviceType = self::PHONE_DEVICE;
		elseif (Yii::$app -> mobileDetect -> isTablet())
			$deviceType = self::TABLET_DEVICE;
		else
			$deviceType = self::DESKTOP_DEVICE;
		//
		return parent::saveModel('\app\models\Session', [
			'UserIP' => Yii::$app -> request -> getUserIP(),
			'UserAgent' => Yii::$app -> request -> getUserAgent(),
			'DeviceType' => $deviceType,
			'StartDate' => $this -> StartDate,
			'EndDate' => $this -> EndDate,
			'UserID' => $this -> UserID,
		]);
	}



	/**
	 *	Удаление модели сессии из базы данных.
	 *	Если модель успешно удалилась, возвращает true.
	 *	Если модель не удалилась, возвращает false.
	 *
	 */
	public function Delete() {
		return parent::deleteModel('\app\models\Session');
	}

}
