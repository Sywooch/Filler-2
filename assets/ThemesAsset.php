<?php

namespace app\assets;

use Yii;
use yii\web\AssetBundle;



/**
 *	Тема для настольной версии сайта.
 *
 */
class ThemesAsset extends AssetBundle {

	/**
	 *
	 *
	 */
	public $sourcePath = '@app/themes/desktop';



	/**
	 *
	 *
	 */
	public $css = [
		'css/site.css',
		'css/game.css',
	];



	/**
	 *
	 *
	 */
	public $js = [

	];



	/**
	 *
	 *
	 */
	public $depends = [
		'yii\web\YiiAsset',
		'app\assets\BootstrapAsset'
	];



	/**
	 *	Получение темы оформления для устройства пользователя.
	 *  Если тип устройства пользователя - смартфон, возвращается @app/themes/mobile,
	 *	иначе @app/themes/desktop.
	 *
	 */
	public function getTheme() {
		return Yii::$app -> mobileDetect -> isPhone() ? '@app/themes/mobile' : '@app/themes/desktop';
	}



	/**
	 *
	 *
	 */
	public function init() {
		parent::init();
		// Установка текущей темы оформления.
		$this -> sourcePath = $this -> getTheme();
	}
}
