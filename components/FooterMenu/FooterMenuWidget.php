<?php

namespace app\components\FooterMenu;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\base\Widget;



/**
 *	Виджет нижнего меню пользователя.
 *	Выводит представление виджета.
 *	Для работы используется Blueprint CSS framework.
 *
 */
class FooterMenuWidget extends Widget {

	/**
	 *	Список пунктов меню (Наименование => Ссылка).
	 *
	 */
	public $ItemList = [];

	/**
	 *	Список размеров пунктов меню (измеряется в колонках).
	 *
	 */
	public $SizeList = [];

	/**
	 *	Стиль меню (1 или 2).
	 *
	 */
	public $Style = 1;

	/**
	 *	Разделитель пунктов меню для стиля 2.
	 *
	 */
	public $Delimiter = '&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;';

	/**
	 *	Общая ширина меню (измеряется в колонках).
	 *
	 */
	public $ColumnCount = 24;



	/**
	 *	Инициализация виджета.
	 *	Подключение и публикация стиля.
	 *
	 */
	public function init() {
		parent::init();
		// echo 'Что за хрень?';
		// 
		// if (Yii::$app -> theme -> getName() == 'mobile')
		//	$fileCSS = '/FooterMenu-mobile.css';
		// else
		 	// $fileCSS = 'FooterMenu.css';
		// $this->view->registerCssFile('@web/components/FooterMenu/views/' . $fileCSS);
		// Yii::$app -> clientScript -> registerCssFile(
		// 	Yii::$app -> assetManager -> publish(
		// 		Yii::getPathOfAlias('ext.FooterMenu.views') . $fileCSS
		// 	)
		// );
	}



	/**
	 *	Запуск виджета.
	 *	Передает представлению настройки виджета:
	 *		Список пунктов меню
	 *		Общая ширина меню
	 *		Список размеров пунктов меню
	 *		Стиль меню
	 *		Разделитель пунктов меню для стиля 2
	 *
	 */
	public function run() {
		return $this -> render('FooterMenu', [
			'ItemList' => $this -> ItemList,
			'ColumnCount' => $this -> ColumnCount,
			'SizeList' => $this -> SizeList,
			'Style' => $this -> Style,
			'Delimiter' => $this -> Delimiter
		]);
	}



	/**
	 *	
	 *
	 */
	public function addLinkClass($String) {
		if (strpos($String, ':') !== false)
			return ['class' => 'dot'];
		else
			return [];
	}

}
