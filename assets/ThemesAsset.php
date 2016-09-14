<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ThemesAsset extends AssetBundle
{
	public $sourcePath = '@app/themes/desktop';
	public $css = [
		'css/site.css',
		'css/game.css',
	];
	public $js = [

	];
	public $depends = [
		'yii\web\YiiAsset',
		'app\assets\BootstrapAsset'
	];
}
