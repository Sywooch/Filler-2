<?php

namespace app\models\models;



use Yii;
use app\models\Map as tableMap;



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
class Map extends LSD {

	/**
	 *	Цветные ячейки.
	 *
	 */
	const COLOR_MAP_TYPE = 1;

	/**
	 *	Блокирующие ячейки.
	 *
	 */
	const BLOCK_MAP_TYPE = 2;
	
	

	/**
	 *	Название карты.
	 *
	 */
	protected $name;

	/**
	 *	Матрица карты.
	 *
	 */
	protected $matrix;

	/**
	 *	X-размер игрового поля.
	 *
	 */
	protected $sizeX;

	/**
	 *	Y-размер игрового поля.
	 *
	 */
	protected $sizeY;

	/**
	 *	Описание карты.
	 *
	 */
	protected $description;

	/**
	 *	Комментарий.
	 *
	 */
	protected $comment;

	/**
	 *	Тип.
	 *
	 */
	protected $type = 1;

	/**
	 *	Статус.
	 *
	 */
	protected $enable = 0;



	/**
	 *
	 *
	 */
	function __construct() {

	}



	/**
	 *	Установка свойств лобби.
	 *
	 */
	public function set($map) {
		foreach ($map as $propertyName => $propertyValue) {
			// Если текущее свойство существует:
			if (property_exists($this, $propertyName))
				// Установка значения текущего свойства.
				$this -> $propertyName = $propertyValue;
		}
	}



	/**
	 *	Возвращает матрицу карты.
	 *
	 */
	public function getMatrix() {
		return $this -> matrix;
	}



	/**
	 *	Возвращает массив всех свойств карты.
	 *
	 */
	public function getPropertyList() {
		return [
			'ID' => $this -> id,
			'name' => $this -> name,
			'sizeX' => $this -> sizeX,
			'sizeY' => $this -> sizeY,
			'matrix' => $this -> matrix,
			'description' => $this -> description,
			'comment' => $this -> comment,
			'type' => $this -> type,
			'enable' => $this -> enable
		];
	}



	/**
	 *	Загружает сессию по указанному идентификатору пользователя $UserID.
	 *	Если сессию успешно загружена, возвращает true.
	 *	Если сессию не загружена, возвращает false.
	 *
	 */
	public function Search($UserID) {
		// Поиск сессии в БД по указанному идентификатору пользователя.
		$dbModel = tableMap::find()
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
		$Result = parent::loadModel($id, '\app\models\Map', [
			'name',
			'matrix',
			'sizeX',
			'sizeY',
			'description',
			'comment',
			'type',
			'enable'
		]);
		$this -> matrix = unserialize($this -> matrix);
		//
		return $Result;
	}



	/**
	 *	Сохранение модели сессии в базу данных.
	 *	Если модель успешно сохранилась, возвращает true.
	 *	Если модель не сохранилась, возвращает false.
	 *
	 */
	public function Save() {
//		// Проверка существования сессии.
//		if (!$this -> isExist())
//			return false;
//		//
		return parent::saveModel('\app\models\Map', [
			'name' => $this -> name,
			'matrix' => serialize($this -> matrix),
			'sizeX' => $this -> sizeX,
			'sizeY' => $this -> sizeY,
			'description' => $this -> description,
			'comment' => $this -> comment,
			'type' => $this -> type,
			'enable' => $this -> enable,
		]);

//		// Поиск сессии в БД по указанному идентификатору пользователя.
//		$dbModel = tableMap::findOne($this -> id);
//		// Если модель не найдена в БД по указанному идентификатору:
//		if (!$dbModel = tableMap::findOne($this -> id))
//			// Создание новой модели.
//			$dbModel = new $ModelName;
//		// Если модель создана:
//		if ($dbModel !== null) {
//			// Копирование свойств в модель.
//			$dbModel -> attributes = $ModelAttributes;
//			// Сохранение модели в БД.
//			$Result = $dbModel -> save();
//			// Получение идентификатора модели в БД.
//			$this -> id = $dbModel -> getPrimaryKey();
//			return $Result;
//		}
//		return false;
	}



	/**
	 *	Удаление модели сессии из базы данных.
	 *	Если модель успешно удалилась, возвращает true.
	 *	Если модель не удалилась, возвращает false.
	 *
	 */
	public function Delete() {
		return parent::deleteModel('\app\models\Map');
	}

}
