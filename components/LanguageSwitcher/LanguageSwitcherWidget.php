<?php

namespace app\components\LanguageSwitcher;

use Yii;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\base\Widget;



/**
 *	Виджет переключателя языка интерфейса.
 *	Выводит представление виджета.
 *	Переключение языка происходит по кругу (ru -> en -> de -> ru).
 *	Список и порядок языков устанавливается в конфигурационном файле (/protected/config/main.php).
 *
 */
class LanguageSwitcherWidget extends Widget {

	/**
	 *	Список всех языков (Код => Наименование).
	 *	Например, ['ru' => 'Ру', 'en' => 'En'].
	 *
	 */
	public $Languages = [];

	/**
	 *	Код текущего языка. Например, 'en' или 'ru'.
	 *
	 */
	public $CurrentLanguageCode;

	/**
	 *	Наименование следующего в переключателе языка.
	 *
	 */
	private $LanguageName;

	/**
	 *	Ссылка следующего в переключателе языка.
	 *
	 */
	private $LanguageLink;



	/**
	 *	Инициализация виджета.
	 *	Подключение и публикация стиля.
	 *	Вычисление наименования и ссылки следующего языка.
	 *
	 */
	public function init() {
		// Если текущая тема для мобильных устройств, подключение соответствующих стилей.
		// if (Yii::app() -> theme -> getName() == 'mobile')
			// $fileCSS = '/LanguageSwitcher-mobile.css';
		// else
			// $fileCSS = '/LanguageSwitcher.css';
		// Подключение и публикация стиля.
		// Yii::$app -> clientScript -> registerCssFile(
		// 	Yii::$app -> assetManager -> publish(
		// 		Yii::getPathOfAlias('ext.LanguageSwitcher.views') . $fileCSS
		// 	)
		// );

		//$this -> registerCssFile(Yii::getAlias('@app') . $fileCSS);

		// echo Html::cssFile(Yii::getAlias('@app') . $fileCSS);
		// print_r($this -> Languages);
		// echo '$this -> language = ' . Yii::$app -> language . ' ';
		// Формирование списка наименований языков (Ру, En, De).
		$LanguageKeys = array_keys($this -> Languages);
		// Формирование списка кодов языков (ru, en, de).
		$LanguageValues = array_values($this -> Languages);
		// Вычисление индекса текущего языка.
		$CurrentLanguageIndex = array_search($this -> CurrentLanguageCode, $LanguageKeys);
		// echo 'CurrentLanguageIndex=' . $CurrentLanguageIndex;
		// Получение наименования следующего языка.
		$this -> LanguageName = ($CurrentLanguageIndex != sizeof($this -> Languages) - 1) ? $LanguageValues[$CurrentLanguageIndex + 1] : $LanguageValues[0];
		// Получение ссылки следующего языка.
		Yii::$app -> language = ($CurrentLanguageIndex != sizeof($this -> Languages) - 1) ? $LanguageKeys[$CurrentLanguageIndex + 1] : $LanguageKeys[0];
		// echo '$this -> language = ' . Yii::$app -> language . ' ';
		$this -> LanguageLink = Yii::$app -> urlManager -> createUrl(Yii::$app -> language . '/' . Yii::$app -> controller -> route);
		// Yii::$app -> language .'/site/index'
		// $this -> LanguageLink = Url::to('site/index', true);
		// echo Yii::$app -> language . '/' . '';
		// $this -> LanguageLink = Url::toRoute([Yii::$app -> language, '']);
		// Восстановление текущего языка.
		Yii::$app -> language = $this -> CurrentLanguageCode;
		// echo '$this -> language = ' . Yii::$app -> language . ' ';
	}



	/**
	 *	Запуск виджета.
	 *	Передает представлению настройки виджета. 
	 *
	 */
	public function run() {
		return $this -> render('LanguageSwitcher', [
			'LanguageLink' => $this -> LanguageLink,
			'LanguageName' => $this -> LanguageName,
		]);
	}

}
