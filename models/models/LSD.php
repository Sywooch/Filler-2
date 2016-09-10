<?php

namespace app\models\models;

use app\models\models\interface2\iLSD;

/**
 * LSD (Load, Save, Delete) class file.
 *
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 * @copyright 2016 Konstantin Poluektov
 *
 */



/**
 * LSD is responsible for saving and loading the model data from the database.
 *
 * @property integer $ID The model ID.
 * @property boolean $AutoSave The option to automatically save the data.
 * 
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 *
 */
abstract class LSD implements iLSD {

	/**
	 *	Опция автосохранения модели в БД.
	 *
	 */
	protected $AutoSave = false;

	/**
	 *	Идентификатор модели.
	 *
	 */
	protected $ID;



	/**
	 *	Автоматическое сохранение в БД модели при уничтожении объекта.
	 *	Необходимо, чтобы была включена опция AutoSave.
	 *
	 */
	function __destruct() {
		// Если включена опция автосохранения:
		if ($this -> AutoSave)
			// Модель сохраняется в БД.
			$this -> Save();
	}



	/**
	 *	Загрузка модели с указанным идентификатором из БД.
	 *
	 */
	protected function loadModel($ID, $ModelName, $ModelAttributes) {
		// Поиск модели в БД по указанному идентификатору.
		//$dbModel = $ModelName::model() -> findByPk($ID);
		$dbModel = $ModelName::findOne($ID);

		// Если модель найдена:
		if ($dbModel !== null) {
			// Копирование свойств из модели.
			foreach ($ModelAttributes as $Attribute)
				$this -> $Attribute = $dbModel -> $Attribute;
			$this -> ID = $ID;
			return true;
		}
		return false;
	}



	/**
	 *	Сохранение модели в БД.
	 *
	 */
	protected function saveModel($ModelName, $ModelAttributes) {
		// Если модель сохраняется в БД впервые:
		if ($this -> ID == null)
			// Создание новой модели.
			$dbModel = new $ModelName;
		// Если модель уже сохранялась в БД:
		else
			// Поиск модели в БД по указанному идентификатору.
			//$dbModel = $ModelName::model() -> findByPk($this -> ID);
			$dbModel = $ModelName::findOne($this -> ID);

		// Если модель создана:
		if ($dbModel !== null) {
			// Копирование свойств в модель.
			$dbModel -> attributes = $ModelAttributes;
			// Сохранение модели в БД.
			$Result = $dbModel -> save();
			// Получение идентификатора модели в БД.
			$this -> ID = $dbModel -> getPrimaryKey();
			return $Result;
		}
		return false;
	}



	/**
	 *	Удаление модели из БД.
	 *
	 */
	protected function deleteModel($ModelName) {
		// Загрузка модели из БД по указанному идентификатору.
		// $dbModel = $ModelName::model() -> findByPk($this -> ID);
		$dbModel = $ModelName::findOne($this -> ID);
		
		// Если модель загрузилась:
		if ($dbModel !== null) {
			// Удаление модели из БД.
			if ($dbModel -> delete()) {
				$this -> AutoSave = false;
				$this -> ID = null;
				return true;
			}
		}
		return false;
	}



	/**
	 *	Возвращает идентификатор.
	 *
	 */
	public function getID() {
		return $this -> ID;
	}

}
