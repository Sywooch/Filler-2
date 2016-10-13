<?php

namespace app\assets;

use Yii;
use yii\web\AssetBundle;



/**
 *	Тема для настольной версии сайта.
 *
 */
class ThemesAsset extends AssetBundle {
	//
	public $sourcePath = '@app/themes/desktop';
	//
	public $css = [
		'css/site.css',
		'css/game.css',
	];
	//
	public $js = [

	];
	//
	public $depends = [
		'yii\web\YiiAsset',
		'app\assets\BootstrapAsset'
	];
	//
	public function getTheme() {
		return !Yii::$app -> mobileDetect -> isMobile() ? '@app/themes/mobile' : '@app/themes/desktop';
	}
	//
	public function init() {
		parent::init();
		$this -> sourcePath = $this -> getTheme();
	}
}
