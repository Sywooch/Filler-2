<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\assets\IndexAsset;
use app\assets\ThemesAsset;
use app\components\ExtController;
// use app\models\User;
// use app\models\ContactForm;

/**
 *	Контроллер управляет регистрацией, авторизацией и восстановлением доступа пользователей.
 *	Выводит представление для главной страницы.
 *
 */
class SiteController extends ExtController {

	/**
	 *	Макет для представлений контроллера.
	 *
	 */
	const INDEX_LAYOUT = '/index';

	/**
	 *	Макет для представлений контроллера.
	 *
	 */
	const STANDART_LAYOUT = '/standart'; // @webroot/themes/desktop/views

	/**
	 *	Код успешного результата.
	 *
	 */
	const SUCCESS = 1;



	/**
	 *	Макет для представлений контроллера.
	 *
	 */
	public $layout = self::STANDART_LAYOUT;



	// public function behaviors()
	// {
	// 	return [
	// 		'access' => [
	// 			'class' => AccessControl::className(),
	// 			'only' => ['logout'],
	// 			'rules' => [
	// 				[
	// 					'actions' => ['logout'],
	// 					'allow' => true,
	// 					'roles' => ['@'],
	// 				],
	// 			],
	// 		],
	// 		'verbs' => [
	// 			'class' => VerbFilter::className(),
	// 			'actions' => [
	// 				'logout' => ['post'],
	// 			],
	// 		],
	// 	];
	// }

	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['index', 'registration'],
				'rules' => [
					// deny all POST requests
					// [
					// 	'allow' => false,
					// 	'verbs' => ['POST']
					// ],

					// Список actions, доступных не авторизованным пользователям.
					[
						'actions' => [
							'registration', 'forgot', 'recovery', 'login', 
							'index', 'help', 'test', 'captcha', 'shortHelp'
						],
						'allow' => true,
						'roles' => ['?'],
					],
					// Список actions, доступных авторизованным пользователям.
					[
						'actions' => [
							'personal', 'Logout', 'index', 
							'help', 'test', 'captcha', 'shortHelp'
						],
						'allow' => true,
						'roles' => ['@'],
					],
					// everything else is denied
				],
			],
		];
	}

	/**
	 *	Устанавливает правила контроля доступа для авторизованных и не авторизованных пользователей.
	 *	При отклонении доступа вызывается метод DeniedRedirect из родительского класса Controller.
	 *
	 */
	// public function accessRules() {
	// 	return array(
	// 		// Список actions, доступных всем пользователям.
	// 		array('allow',
	// 			'actions' => array('Index', 'Help', 'Test', 'Captcha', 'ShortHelp'),
	// 			'users' => array('*'),
	// 		),
	// 		// Список actions, доступных не авторизованным пользователям.
	// 		array('allow',
	// 			'actions' => array('Registration', 'Forgot', 'Recovery', 'Login'),
	// 			'users' => array('?'),
	// 		),
	// 		// Список actions, доступных авторизованным пользователям.
	// 		array('allow',
	// 			'actions' => array('Personal', 'Logout'),
	// 			'users' => array('@'),
	// 		),
	// 		// Отклонение доступа всем остальным пользователям.
	// 		array('deny',
	// 			'users' => array('*'),
	// 			'deniedCallback' => array($this, 'DeniedRedirect'),
	// 		),
	// 	);
	// }

	public function actions()
	{
		// return [
		//     'error' => [
		//         'class' => 'yii\web\ErrorAction',
		//     ],
		//     'captcha' => [
		//         'class' => 'yii\captcha\CaptchaAction',
		//         'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
		//     ],
		// ];

		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'transparent' => true,
				'backColor' => 0x323232,
				'foreColor' => 0xAAAAAA, // 0x727272
				'testLimit' => 1,
			]
		];
	}



	/**
	 *	Устанавливает фильтры.
	 *	Включен фильтр контроля доступа.
	 *
	 */
	// public function filters() {
	// 	return array(
	// 		// Фильтр для AJAX-запросов.
	// 		'ajaxOnly + Login',
	// 		'ajaxOnly + ShortHelp',
	// 		// Фильтр контроля доступа.
	// 		'accessControl'
	// 	);
	// }







	/**
	 *	Настройка параметров капчи.
	 *
	 */
	// public function actions() {
	// 	return array(
	// 		'captcha' => array(
	// 			'class' => 'CCaptchaAction',
	// 			'transparent' => true,
	// 			'backColor' => 0x323232,
	// 			'foreColor' => 0xAAAAAA, // 0x727272
	// 			'testLimit' => 1,
	// 		)
	// 	);
	// }



	/**
	 *	Отображение ошибки.
	 *
	 */
	// public function actionError() {
	// 	$this -> layout = self::INDEX_LAYOUT;
	// 	// Если произошла ошибка:
	// 	if ($error = Yii::$app -> errorHandler -> error)
	// 		// Вывод представления (ошибка).
	// 		return $this -> render('error', $error);
	// }



	/**
	 *	Подготавливает и передает представлению общую статистическую информацию
	 *	(количество игр, количество игроков).
	 *	
	 *	Запрашивает из БД общее количество проведенных игр 
	 *	и количество зарегистрированных игроков.
	 *
	 */
	public function actionIndex() {

		// IndexAsset::register($this -> view);
		// ThemesAsset::register($this -> view);

		// Подключение и публикация библиотеки для валидации форм ввода данных.
		// Yii::$app -> clientScript -> registerScriptFile(
		// 	Yii::$app -> assetManager -> publish(
		// 		Yii::getPathOfAlias('ext.JS') . '/jquery.validate.js'
		// 	)
		// );
		// // Подключение и публикация библиотеки для работы с формами ввода данных.
		// Yii::$app -> clientScript -> registerScriptFile(
		// 	Yii::$app -> assetManager -> publish(
		// 		Yii::getPathOfAlias('ext.JS') . '/jquery.form.js'
		// 	)
		// );
		// // Подключение и публикация скрипта для валидации формы авторизации.
		// Yii::$app -> clientScript -> registerScriptFile(
		// 	Yii::$app -> assetManager -> publish(
		// 		(YII_DEBUG ? './protected/js/site/index.js' : './protected/js/site/index.min.js')
		// 	)
		// );
		// // Передача скрипту базового пути и массива сообщений об ошибке.
		// Yii::$app -> clientScript -> registerScript(
		// 	'Authorization',
		// 	"var BASE_URL = '" . Yii::$app -> request -> baseUrl . "';
		// 	var ERROR_MESSAGE = [];
		// 	ERROR_MESSAGE[0] = '" . Yii::t('Dictionary', 'Enter a e-mail address') . "';
		// 	ERROR_MESSAGE[1] = '" . Yii::t('Dictionary', 'Enter a password') . "';
		// 	ERROR_MESSAGE[2] = '" . Yii::t('Dictionary', 'Incorrect e-mail address') . "';",		
		// 	CClientScript::POS_HEAD
		// );
		// Вывод представления (главная страница).
		$this -> layout = self::INDEX_LAYOUT;
		return $this -> render('index');
	}



	/**
	 *	Вывод представления (Помощь).
	 *
	 */
	public function actionHelp() {
		// Вывод представления (Помощь).
		return $this -> render('help');
	}



	/**
	 *	Работает в формате AJAX-запросов от клиента.
	 *	Получает запрос на получение краткой справочной информации.
	 *
	 */
	public function actionShortHelp() {
		// Если тип запроса AJAX:
		if (Yii::app() -> request -> isAjaxRequest) {
			// Возвращение краткой справочной информации в формате HTML.
			echo $this -> renderPartial('shorthelp');
			Yii::app() -> end();
		}
	}



	/**
	 *	Работает в формате AJAX-запросов от клиента.
	 *	Получает запрос на авторизацию пользователя с указанной 
	 *	электронной почтой $_POST['Email'] и паролем $_POST['Password'].
	 *
	 *		Если авторизация пользователя пройдена успешно, 
	 *		начинается авторизованная сессия. Возвращается 1. 
	 *
	 *		Если авторизации не прошла, возвращается информация об ошибке 
	 *		(код и описание ошибки) в формате JSON.
	 *
	 */
	public function actionLogin() {
		// $request = Yii::$app->request;
		// Получение POST данных.
		$Email = Yii::$app -> request -> post('Email');
		// $Email = $request->post('Email'); 
		$Password = Yii::$app -> request -> post('Password');
		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			$Player = new UserIdentity($Email, $Password);
			// Если авторизация пользователя пройдена успешно:
			if ($Player -> authenticate()) {
				// Начинается авторизованная сессия.
				Yii::$app -> user -> login($Player);
				// Запись в соответствующий журнал логов информационного сообщения.
				Yii::log('Авторизация пользователя [ ' . $Email . ' ].', 'info', 'user.login');
				// Возвращается код успешной авторизации.
				echo(self::SUCCESS);
			}
			// Если авторизация не пройдена, возвращается информация об ошибке.
			else {
				// Запись в соответствующий журнал логов информационного сообщения.
				Yii::log('Отклоненная авторизация пользователя [ ' . $Email . ' ].', 'info', 'user.login');
				// Получение кода и описания ошибки.
				$Result['ErrorCode'] = $Player -> errorCode;
				$Result['ErrorMessage'] = $Player -> errorMessage;
				// Возвращается информация об ошибке.
				echo(json_encode($Result));
			}
			Yii::$app -> end();
		}
		else {
			// $this -> render();
		}
	}



	/**
	 *	Завершает авторизованную сессию.
	 *	Перенаправляет пользователя на главную страницу.
	 *
	 */
	public function actionLogout() {
		// Завершение авторизованной сессии.
		Yii::app() -> user -> logout();
		// Запись в соответствующий журнал логов информационного сообщения.
		// Yii::log('Выход пользователя [ ' . $Email . ' ].', 'info', 'user.logout');
		// Перенаправление на домашнюю страницу.
		$this -> redirect(Yii::app() -> homeUrl);
	}



	/**
	 *	Получает запрос на восстановление доступа для пользователя с указанной 
	 *	электронной почтой $_POST['Email'].
	 *
	 *	Вывод представления (Восстановление доступа: Шаг 1 из 2).
	 *
	 */
	public function actionForgot() {
		// 
		$Model = new tableUser('forgot');
		// AJAX-проверка.
		$this -> performAjaxValidation($Model);
		// Если получен адрес электронной почты:
		if (isset($_POST['tableUser']['Email'])) {
			$Email = $_POST['tableUser']['Email'];
			$User = new User();
			// Если пользователь с указанным адресом электронной почты найден:
			if ($User -> SearchByEmail($Email)) {
				// Генерирование идентификационного кода.
				$Code = $User -> setPasswordRecovery();
				// Отправка пользователю письма для восстановления доступа.
				$EmailNotification = new EmailNotification($Email, 'forgot', array(
					'Link' => Yii::app() -> getBaseUrl(true) . '/site/recovery?code=' . $Code,
					'PlayerEmail' => $Email,
				));
				// Если письмо не отправлено:
				if (!$EmailNotification -> Send())
					// Запись в соответствующий журнал логов сообщения об ошибке.
					Yii::log('Ошибка при отправке пользователю [ ' . $Email . ' ] письма для восстановления доступа.', 'error', 'email');
				$Result = TRUE;
				// Запись в соответствующий журнал логов информационного сообщения.
				Yii::log('Запрос восстановления доступа для пользователя [ ' . $Email . ' ].', 'info', 'user.forgot');
			}
			// Если пользователь с указанным адресом электронной почты не найден:
			else {
				// Возвращается ошибка.
				$Model -> addError('Email', Yii::t('Dictionary', 'Unknown e-mail address'));
				$Model -> setAttributes(array('Email' => $Email));
			}
		}
		// Вывод представления (Восстановление пароля).
		$this -> render('forgot', array(
			'Model' => $Model,
			'Result' => $Result,
		));
	}



	/**
	 *	Получает запрос на восстановление доступа для пользователя  
	 *	по указанному идентификационному коду.
	 *
	 *	Вывод представления (Восстановление доступа: Шаг 2 из 2).
	 *
	 *		Пример ссылки: http://localhost/colours/site/recovery?code=7c7a128d4ef62d90b4cea79b85aeeb72
	 *
	 */
	public function actionRecovery() {
		// 
		$Model = new tableUser('recovery');
		// AJAX-проверка.
		$this -> performAjaxValidation($Model);
		// 
		$User = new User();		
		// Если получен действующий идентификационный код:
		if (isset($_GET['code']) && $User -> SearchByRecoveryCode($_GET['code'], 60 * 60)) {
			// Если получены данные пользователя:
			if (isset($_POST['tableUser'])) {
				// Поиск пользователя в БД по указанному идентификатору.
				$Model = tableUser::model() -> findByPk($User -> getID());
				// Установка сценария восстановления доступа пользователя.
				$Model -> setScenario('recovery');
				// Полученные данные из POST-запроса копируются в модель.
				$Model -> attributes = $_POST['tableUser'];
				// Пароль копируется в чистом виде для отправки в письме.
				$Password = $Model -> Password;
				// Результат для представления: успешное восстановление доступа.
				$Result = TRUE;
				// Если новый пароль пользователя успешно сохранен:
				if ($Model -> save()) {
					// Отправка пользователю письма об успешной смене пароля и восстановлении доступа.
					$EmailNotification = new EmailNotification($Model -> Email, 'recovery', array(
						'PlayerName' => $Model -> Name,
						'PlayerEmail' => $Model -> Email,
						'PlayerPassword' => $Password,
					));
					// Если письмо не отправлено:
					if (!$EmailNotification -> Send())
						// Запись в соответствующий журнал логов сообщения об ошибке.
						Yii::log('Ошибка при отправке пользователю [ ' . $Model -> Email . ' ] письма об успешной смене пароля и восстановлении доступа.', 'error', 'email');
					// Запись в соответствующий журнал логов информационного сообщения.
					Yii::log('Восстановление доступа для пользователя [ ' . $Model -> Email . ' ].', 'info', 'user.recovery');
				}
			}

		}
		// Если идентификационный код не получен или является недействительным:
		else
			// Перенаправление для повторного запроса восстановления доступа.
			$this -> redirect($this -> createUrl('/site/forgot/'));
		// Вывод представления:
		$this -> render('recovery', array(
			'Model' => $Model,
			'Result' => $Result,
		));
	}



	/**
	 *	Если получены регистрационные данные нового пользователя $_POST['tableUser'], 
	 *	полученные в запросе данные проверяются.
	 *
	 *		Если все данные соответстуют требованиям, новый пользователь регистрируется в БД.
	 *		Отправляется письмо на электронную почту зарегистрированного пользователя.
	 *		Новый пользователь автоматически авторизуется и перенаправляется 
	 *		на игровую страницу.
	 *
	 *		Если в регистрационных данных есть несоответствия требованиям,
	 *		представлению возвращается заполненная регистрационная форма 
	 *		с указанием ошибок.
	 *
	 *	Если получен запрос регистрационной формы, новая форма подготавливается 
	 *	и передается представлению.
	 *
	 */
	public function actionRegistration() {
		// 
		$Model = new \app\models\User; //('registration');
		// AJAX-проверка.
		// $this -> performAjaxValidation($Model);
		// Если получены POST данные регистрационной формы:
		if (isset($_POST['User'])) {
			// Полученные данные из POST-запроса копируются в модель.
			$Model -> attributes = $_POST['User'];
			$Model -> ControlCode = $_POST['User']['ControlCode'];
			// Пароль копируется в чистом виде для отправки в письме.
			$Password = $Model -> Password;
			// Если новый пользователь успешно сохранен в БД:
			if ($Model -> save()) {
				// Отправка игроку письма об успешной регистрации.
				$EmailNotification = new EmailNotification($Model -> Email, 'registration', array(
					'PlayerName' => $Model -> Name,
					'PlayerEmail' => $Model -> Email,
					'PlayerPassword' => $Password,
				));
				// Если письмо не отправлено:
				if (!$EmailNotification -> Send())
					// Запись в соответствующий журнал логов сообщения об ошибке.
					Yii::log('Ошибка при отправке пользователю [ ' . $Model -> Email . ' ] письма об успешной регистрации.', 'error', 'email');
				// Запись в соответствующий журнал логов информационного сообщения.
				Yii::log('Регистрация нового пользователя [ ' . $Model -> Email . ' ].', 'info', 'user.registration');
				// Автоматическая авторизация нового пользователя.
				$Player = new UserIdentity($Model -> Email, $Password);
				if ($Player -> authenticate()) {
					Yii::app() -> user -> login($Player);
					// Перенаправление на страницу игры.
					$this -> redirect($this -> createUrl('/game/game/'));
				}
			}
		}
		// Отображение страницы регистрации.
		return $this -> render('registration', array(
			'Model' => $Model
		));
	}



	/**
	 *	Если получены обновленные личные данные пользователя $_POST['tableUser'], 
	 *	полученные в запросе данные проверяются.
	 *
	 *		Если все данные соответстуют требованиям, данные пользователя обновляются в БД.
	 *		Отправляется письмо на электронную почту пользователя.
	 *
	 *		Если в обновленных данных есть несоответствия требованиям,
	 *		представлению возвращается заполненная форма с указанием ошибок.
	 *
	 *	Если получен запрос формы для редактирования личных данных, новая форма подготавливается 
	 *	и передается представлению.
	 *
	 */
	public function actionPersonal() {
		// Поиск пользователя в БД по указанному идентификатору.
		$Model = tableUser::model() -> findByPk(Yii::app() -> user -> getId());
		// Если получены POST данные формы с личными данными:
		if (isset($_POST['tableUser'])) {
			// Если в POST данных отсутствуют пароль и проверочный пароль:
			if ($_POST['tableUser']['Password'] == NULL && $_POST['tableUser']['ControlPassword'] == NULL) {
				// Установка сценария обновления данных пользователя без пароля.
				$Model -> setScenario('update');
				// Удаление из POST данных пустых атрибутов.
				unset($_POST['tableUser']['Password']);
				unset($_POST['tableUser']['ControlPassword']);
				// Установка информации о неизменности пароля для отправки в письме.
				$Password = Yii::t('Dictionary', 'Unchanged');
			}
			else {
				// Установка сценария обновления данных пользователя с паролем.
				$Model -> setScenario('update-password');
				// Новый пароль копируется в чистом виде для отправки в письме.
				$Password = $_POST['tableUser']['Password'];
			}
			// AJAX-проверка.
			$this -> performAjaxValidation($Model);
			// Полученные данные из POST-запроса копируются в модель.
			$Model -> attributes = $_POST['tableUser'];
			// Если данные пользователя успешно сохранены в БД:
			if ($Model -> save()) {
				$Result = TRUE;
				// Отправка игроку письма об успешном изменении персональных данных.
				$EmailNotification = new EmailNotification($Model -> Email, 'personal', array(
					'PlayerName' => $Model -> Name,
					'PlayerEmail' => $Model -> Email,
					'PlayerPassword' => $Password,
				));
				// Если письмо не отправлено:
				if (!$EmailNotification -> Send())
					// Запись в соответствующий журнал логов сообщения об ошибке.
					Yii::log('Ошибка при отправке пользователю [ ' . $Model -> Email . ' ] письма об изменении личных данных.', 'error', 'email');
				// Запись в соответствующий журнал логов информационного сообщения.
				Yii::log('Изменение личных данных пользователя [ ' . $Model -> Email . ' ].', 'info', 'user.personal');
			}
		}
		// Удаление из модели пароля и проверочного пароля, 
		// чтобы они не отображались в форме представления.
		$Model -> Password = NULL;
		$Model -> ControlPassword = NULL;
		// Отображение страницы.
		$this -> render('personal', array(
			'Model' => $Model,
			'Result' => $Result
		));
	}



	/**
	 *	Если получены личные данные пользователя $_POST['tableUser'] в AJAX-запросе, 
	 *	полученные в запросе данные проверяются и возвращается результат проверки.
	 *
	 */
	protected function performAjaxValidation($Model) {
		// Если это AJAX-запрос для проверки данных формы, введенных пользователем:
		// if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
		// 	// Изменение первоначального сценария 'registration' на 'registration-ajax'
		// 	// для того, чтобы при AJAX-запросах CAPTCHA не обновлялась (изменялась).
		// 	if ($Model -> getScenario() == 'registration')
		// 		$Model -> setScenario('registration-ajax');
		// 	// Возвращается результат проверки данных формы.
		// 	// echo(CActiveForm::validate($Model));
		// 	return CActiveForm::validate($Model);
		// 	// Yii::app() -> end();
		// }

		if (Yii::$app->request->isAjax && $Model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($Model);
		}
	}



	// public function actionTest() {
	// 	if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
	// 		Yii::$app->response->format = Response::FORMAT_JSON;
	// 		return ActiveForm::validate($model);
	// 	}
	// }

}
