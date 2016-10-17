<?php

namespace app\components\LanguageSwitcher\assets;

use Yii;
use yii\web\AssetBundle;



/**
 *
 *
 */
class LanguageSwitcherAsset extends AssetBundle {
	//
	public $sourcePath = '@app/components/LanguageSwitcher/views';
	//
	public $css = [
		'LanguageSwitcher.css',
	];
	//
	public $js = [
	];
	//
	public $depends = [
		'yii\web\YiiAsset',
	];
	//
	public function getTheme() {
		return Yii::$app -> mobileDetect -> isMobile() ? 'LanguageSwitcher-mobile.css' : 'LanguageSwitcher.css';
	}
	//
	public function init() {
		parent::init();
		$this -> css = [$this -> getTheme()];
	}
}
