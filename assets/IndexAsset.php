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
class IndexAsset extends AssetBundle {
	public $sourcePath = '@app/js/site';
	public $css = [
	];
	public $js = [
		YII_DEBUG ? 'index.js' : 'index.min.js',
		'jquery.validate.js',
		'jquery.form.js',
	];
	public $depends = [
		'yii\web\YiiAsset',
	];
}
