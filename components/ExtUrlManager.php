<?php

/**
 * UrlManager class file.
 *
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 * @copyright 2016 Konstantin Poluektov
 *
 */

namespace app\components;

use Yii;
use yii\web\UrlManager;

/**
 * UrlManager extended class CUrlManager.
 *
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 *
 */
class ExtUrlManager extends UrlManager {

	/**
	 *	Генерация URL, с учетом текущего языка (переопределенный метод).
	 *
	 */
	public function createUrl($params) {
		if (empty($params['language'])) {
			$params['language'] = Yii::$app -> language;
		}
		return $this -> fixPathSlashes(parent::createUrl($params));
	}



	/**
	 *	Обратное восстановление экранированных слэшей.
	 *	Замена в URL кода '%2F' на '/'.
	 *
	 */
	protected  function fixPathSlashes($url) {
		return preg_replace('|\%2F|i', '/', $url);
	}

}
