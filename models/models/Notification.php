<?php

namespace app\models\models;



use Yii;
use app\models\Notification as tableNotification;



/**
 * Notification class file.
 *
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 * @copyright 2016 Konstantin Poluektov
 *
 */

 
 
/**
 * Notification manages the notification.
 *
 * 
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 *
 */
class Notification extends tableNotification {
	


	/**
	 *
	 *
	 */
	function __construct($UserID = null) {
//		$this -> UserID = $UserID;
	}



	/**
	 *	Начинает новую сессию.
	 *
	 */
	public function Start() {
//		$this -> Save();
	}



	/**
	 *	Продолжает сессию.
	 *	Фиксирует текущее время сессии.
	 *
	 */
//	public function Update() {
//		$this -> EndDate = date("Y-m-d H:i:s");
//		$this -> Save();
//	}



	/**
	 *	Завершает сессию.
	 *	Устанавливает время окончания сессии.
	 *
	 */
	public function Stop() {
//		$this -> EndDate = date("Y-m-d H:i:s");
//		$this -> Save();
	}



	/**
	 *	Проверяет, запущена ли сессия.
	 *
	 */
	public function isExist() {
//		if ($this -> UserID)
//			return true;
//		return false;
		return 787;
	}



	/**
	 *	Загружает сессию по указанному идентификатору пользователя $UserID.
	 *	Если сессию успешно загружена, возвращает true.
	 *	Если сессию не загружена, возвращает false.
	 *
	 */
//	public function Search($UserID) {
//		// Поиск сессии в БД по указанному идентификатору пользователя.
//		$dbModel = tableSession::find()
//			-> where(['UserID' => $UserID])
//			-> orderBy('id DESC')
//			-> one();
//		// Если сессия найдена:
//		if ($dbModel !== null) {
//			// Если модель загрузилась:
//			if ($this -> Load($dbModel -> id)) {
//				// Инициализация идентификатора сессии.
//				$this -> id = $dbModel -> id;
//				return true;
//			}
//		}
//		return false;
//	}



	/**
	 *	Загрузка модели сессии из базы данных.
	 *	Если модель успешно загрузилась, возвращает true.
	 *	Если модель не загрузилась, возвращает false.
	 *
	 */
//	public function Load($id) {
//		return parent::loadModel($id, '\app\models\Session', [
//			'UserIP',
//			'UserAgent',
//			'DeviceType',
//			'StartDate',
//			'EndDate',
//			'UserID'
//		]);
//	}



	/**
	 *	Сохранение модели сессии в базу данных.
	 *	Если модель успешно сохранилась, возвращает true.
	 *	Если модель не сохранилась, возвращает false.
	 *
	 */
//	public function Save() {
//		// Проверка существования сессии.
//		if (!$this -> isExist())
//			return false;
//		//
//		return parent::saveModel('\app\models\Session', [
//			'UserIP' => Yii::$app -> request -> getUserIP(),
//			'UserAgent' => Yii::$app -> request -> getUserAgent(),
//			'DeviceType' => Yii::$app -> mobileDetect -> getDeviceType(),
//			'StartDate' => $this -> StartDate,
//			'EndDate' => $this -> EndDate,
//			'UserID' => $this -> UserID,
//		]);
//	}



	/**
	 *	Удаление модели сессии из базы данных.
	 *	Если модель успешно удалилась, возвращает true.
	 *	Если модель не удалилась, возвращает false.
	 *
	 */
//	public function Delete() {
//		return parent::deleteModel('\app\models\Session');
//	}

}
