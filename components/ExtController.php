<?php

namespace app\components;

use Yii;

use yii\web\Controller;
use yii\web\Request;
use yii\web\Cookie;



/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 *
 */
class ExtController extends Controller {

	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 *
	 */
	public $layout = '//layouts/column1';

	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 *
	 */
	public $menu = [];

	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 *
	 */
	public $breadcrumbs = [];

	/**
	 *	Устройство пользователя.
	 *
	 */
	public $ClientDevice;



	/**
	 *	Инициализация контроллера (переопределенный метод).
	 *
	 */
	public function init() {

		// // Подключение и публикация стиля.
		// Yii::app() -> clientScript -> registerCssFile(
		// 	Yii::app() -> assetManager -> publish(
		// 		Yii::getPathOfAlias('ext.bootstrap.css') . '/bootstrap.css'
		// 	)
		// );

		// // Подключение и публикация общей библиотеки.
		// Yii::app() -> clientScript -> registerCoreScript('jquery');
		// // Yii::app() -> ClientScript -> registerScriptFile(
		// // 	"https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"
		// // );

		// // Подключение и публикация общей библиотеки.
		// Yii::app() -> clientScript -> registerScriptFile(
		// 	Yii::app() -> assetManager -> publish(
		// 		Yii::getPathOfAlias('ext.bootstrap.js') . (YII_DEBUG ? '/bootstrap.js' : '/bootstrap.min.js')
		// 	)
		// );

		// Установка текущего языка.
		$this -> LanguageInit();

		// // Устройство пользователя.
		// $this -> ClientDevice = Yii::$app -> mobileDetect;

		// // Если устройство пользователя мобильное, устанавливается мобильная тема приложения.
		// if ($this -> ClientDevice -> isMobile() && !$this -> ClientDevice -> isTablet())
		// 	Yii::$app -> setTheme('mobile');

		parent::init();
	}



	/**
	 *	Регистрация отклонения доступа пользователя и перенаправление на начальную страницу.
	 *
	 */
	public function DeniedRedirect($actionName) {
		// Получение имени пользователя.
		$User = Yii::$app -> user -> isGuest ? 'Гость' : Yii::$app -> user -> identity -> Email;
		// Запись в соответствующий журнал логов информационного сообщения.
		Yii::info('Доступ пользователя [ ' . $User . ' ] к действию [ ' . $actionName . ' ] отклонен.', 'user.access');
		// Перенаправление на стартовую страницу.
		return $this -> redirect(Yii::$app -> homeUrl);
	}



	/**
	 *	Устанавливает текущий язык в следующем порядке:
	 *
	 *		Если язык указан в запросе, язык устанавливается из запроса.
	 *		
	 *		Если язык указан в куке, язык устанавливается из куки.
	 *
	 *		Иначе устанавливается язык по умолчанию.
	 *
	 *	Если язык еще не сохранен в куке или текущий язык отличается 
	 *	от сохраненного в куке, значение куки обновляется.
	 *
	 */
	private function LanguageInit() {
		// Получение куки.
		$CookieLanguage = Yii::$app -> request -> cookies['language'] -> value;
		// echo 'CookieLanguage=' . $CookieLanguage;
		$Cookie = Yii::$app -> response -> cookies;
		// // Если язык указан в запросе:
		if (!empty($_GET['language']))
			// Текущий язык устанавливается из запроса.
			Yii::$app -> language = Yii::$app -> request -> get('language');
		// Если язык не указан в запросе, но указан в куке:
		else if (!empty($CookieLanguage))
			// Текущий язык устанавливается из куки.
			Yii::$app -> language = $CookieLanguage;
		// Если язык еще не сохранен в куке или текущий язык отличается от сохраненного в куке:
		if (empty($CookieLanguage) || $CookieLanguage != Yii::$app -> language) {
			// Установка значения куки из текущего языка.
			$Cookie -> add(new \yii\web\Cookie([
				'name' => 'language',
				'value' => Yii::$app -> language,
				// Установка срока действия куки 1 год.
				'expire' => time() + (365 * 24 * 60 * 60)
			]));
			// Установка срока действия куки 1 год.
			// $Cookie -> expire = time() + (365 * 24 * 60 * 60);
			// Обновление значения куки.
			// Yii::$app -> request -> cookies['language'] = $Cookie;
		}
	}

}
