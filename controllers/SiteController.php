<?php

namespace app\controllers;

use Yii;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\Url;

use app\assets\IndexAsset;
use app\assets\ThemesAsset;

use app\components\ExtController;
use app\components\EmailNotification;
use app\components\UserIdentity;

use app\models\UploadImage;
use app\models\Bot as tableBot;
use app\models\User as tableUser;
use app\models\Lobby as tableLobby;
use app\models\LobbyPlayer as tableLobbyPlayer;

use app\models\models\Bot;
use app\models\models\Session;



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
	const STANDART_LAYOUT = '/standart';

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



	/**
	 *	Устанавливает правила контроля доступа для авторизованных и не авторизованных пользователей.
	 *	При отклонении доступа вызывается метод DeniedRedirect из родительского класса Controller.
	 *
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				// Обработчик отклонения доступа к действию.
				'denyCallback' => function ($rule, $action) {
					$this -> DeniedRedirect($action -> actionMethod);
				},
				// Список действий, к которым относятся данные правила доступа.
				'only' => [
					'index', 'registration', 'login', 'logout', 
					'forgot', 'recovery', 'personal', 'test', 'shorthelp', 'help'
				],
				// Описание правил доступа.
				'rules' => [
					// Список действий, доступных не авторизованным пользователям.
					[
						'actions' => [
							'registration', 'forgot', 'recovery', 'login', 
							'index', 'help', 'captcha', 'shorthelp', 'test'
						],
						'allow' => true,
						'roles' => ['?'],
					],
					// Список действий, доступных авторизованным пользователям.
					[
						'actions' => [
							'personal', 'logout', 'index', 
							'help', 'captcha', 'shorthelp', 'test'
						],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}

	

	/**
	 *	
	 *
	 */
	public function beforeAction($action) {
		// Регистрация начала сессии текущего пользователя.
		$playerSession = new Session();
		// Поиск текущей сессии по идентификатору текущего пользователя.
		if (Yii::$app -> user -> getId() && $playerSession -> Search(Yii::$app -> user -> getId()))
			$playerSession -> Update();
		return true;
	}



	/**
	 *	Настройка параметров капчи.
	 *
	 */
	public function actions() {
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'width' => Yii::$app -> mobileDetect -> isPhone() ? '300px' : '180px',
				'height' => Yii::$app -> mobileDetect -> isPhone() ? '100px' : '50px',
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
	 *	Отображение ошибки.
	 *
	 */
	// public function actionError() {
		// $this -> layout = self::INDEX_LAYOUT;
		// Если произошла ошибка:
		// if ($error = Yii::$app -> errorHandler -> error)
			// Вывод представления (ошибка).
			// return $this -> render('error', $error);
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
	public function actionShorthelp() {
		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			// Возвращение краткой справочной информации в формате HTML.
			echo $this -> renderPartial('shorthelp');
			Yii::$app -> end();
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
				// Регистрация начала сессии текущего пользователя.
				$playerSession = new Session(Yii::$app -> user -> getId());
				$playerSession -> Start();
				// Запись в соответствующий журнал логов информационного сообщения.
				Yii::info('Авторизация пользователя [ ' . $Email . ' ].', 'user.login');
				// Возвращается код успешной авторизации.
				echo(self::SUCCESS);
			}
			// Если авторизация не пройдена, возвращается информация об ошибке.
			else {
				// Запись в соответствующий журнал логов информационного сообщения.
				Yii::info('Отклоненная авторизация пользователя [ ' . $Email . ' ].', 'user.login');
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
		// Регистрация начала сессии текущего пользователя.
		$playerSession = new Session();
		// Поиск текущей сессии по идентификатору текущего пользователя.
		$playerSession -> Search(Yii::$app -> user -> getId());
		$playerSession -> Stop();
		// Запись в соответствующий журнал логов информационного сообщения.
		Yii::info('Выход пользователя [ ' . Yii::$app -> user -> identity -> Email . ' ].', 'user.logout');
		// Завершение авторизованной сессии.
		Yii::$app -> user -> logout();
		// Перенаправление на домашнюю страницу.
		$this -> redirect(Yii::$app -> getHomeUrl());
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
		$Model = new tableUser(['scenario' => tableUser::FORGOT]); // tableUser('forgot')
		// AJAX-проверка.
		// $this -> performAjaxValidation($Model);
		// Если получен адрес электронной почты:
		if (isset($_POST['User']['Email'])) {
			$Email = $_POST['User']['Email'];
			$User = new \app\models\models\User();
			// Если пользователь с указанным адресом электронной почты найден:
			if ($User -> SearchByEmail($Email)) {
				// Генерирование идентификационного кода.
				$Code = $User -> setPasswordRecovery();
				// Отправка пользователю письма для восстановления доступа.
				$EmailNotification = new EmailNotification($Email, 'forgot', [
					'Link' => Yii::$app -> urlManager -> getHostInfo() . '/site/recovery?code=' . $Code,
					'PlayerEmail' => $Email,
				]);
				// Если письмо не отправлено:
				if (!$EmailNotification -> Send())
					// Запись в соответствующий журнал логов сообщения об ошибке.
					Yii::error('Ошибка при отправке пользователю [ ' . $Email . ' ] письма для восстановления доступа.', 'email');
				// 
				$Result = TRUE;
				// Запись в соответствующий журнал логов информационного сообщения.
				Yii::info('Запрос восстановления доступа для пользователя [ ' . $Email . ' ].', 'user.forgot');
			}
			// Если пользователь с указанным адресом электронной почты не найден:
			else {
				// Возвращается ошибка.
				$Model -> addError('Email', Yii::t('Dictionary', 'Unknown e-mail address'));
				$Model -> setAttributes(['Email' => $Email]);
			}
		}
		// Вывод представления (Восстановление пароля).
		return $this -> render('forgot', [
			'Model' => $Model,
			'Result' => $Result,
		]);
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
		$Model = new tableUser(['scenario' => tableUser::RECOVERY]);
		// AJAX-проверка.
		// $this -> performAjaxValidation($Model);
		// 
		$User = new \app\models\models\User();
		// Если получен действующий идентификационный код:
		if (isset($_GET['code']) && $User -> SearchByRecoveryCode($_GET['code'], 60 * 60)) {
			// Если получены данные пользователя:
			if (isset($_POST['User'])) {
				// Поиск пользователя в БД по указанному идентификатору.
				// $Model = tableUser::model() -> findByPk($User -> getID());
				$Model = tableUser::findOne($User -> getID());
				// Установка сценария восстановления доступа пользователя.
				$Model -> setScenario(tableUser::RECOVERY);
				// Полученные данные из POST-запроса копируются в модель.
				$Model -> attributes = $_POST['User'];
				// Пароль копируется в чистом виде для отправки в письме.
				$Password = $Model -> Password;
				// Если новый пароль пользователя успешно сохранен:
				if ($Model -> save()) {
					// Результат для представления: успешное восстановление доступа.
					$Result = TRUE;
					// Отправка пользователю письма об успешной смене пароля и восстановлении доступа.
					$EmailNotification = new EmailNotification($Model -> Email, 'recovery', [
						'PlayerName' => $Model -> Name,
						'PlayerEmail' => $Model -> Email,
						'PlayerPassword' => $Password,
					]);
					// Если письмо не отправлено:
					if (!$EmailNotification -> Send())
						// Запись в соответствующий журнал логов сообщения об ошибке.
						Yii::error('Ошибка при отправке пользователю [ ' . $Model -> Email . ' ] письма об успешной смене пароля и восстановлении доступа.', 'email');
					// Запись в соответствующий журнал логов информационного сообщения.
					Yii::info('Восстановление доступа для пользователя [ ' . $Model -> Email . ' ].', 'user.recovery');
				}
			}

		}
		// Если идентификационный код не получен или является недействительным:
		else
			// Перенаправление для повторного запроса восстановления доступа.
			// $this -> redirect($this -> createUrl('/site/forgot/'));
			$this -> redirect(Url::to(['site/forgot']));
		// Вывод представления:
		return $this -> render('recovery', [
			'Model' => $Model,
			'Result' => $Result,
		]);
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
		$Model = new tableUser(['scenario' => tableUser::REGISTRATION]); //('registration');
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
				$EmailNotification = new EmailNotification($Model -> Email, 'registration', [
					'PlayerName' => $Model -> Name,
					'PlayerEmail' => $Model -> Email,
					'PlayerPassword' => $Password,
				]);
				// Если письмо не отправлено:
				if (!$EmailNotification -> Send())
					// Запись в соответствующий журнал логов сообщения об ошибке.
					Yii::error('Ошибка при отправке пользователю [ ' . $Model -> Email . ' ] письма об успешной регистрации.', 'email');
				// Запись в соответствующий журнал логов информационного сообщения.
				Yii::info('Регистрация нового пользователя [ ' . $Model -> Email . ' ].', 'user.registration');
				// Автоматическая авторизация нового пользователя.
				$Player = new UserIdentity($Model -> Email, $Password);
				if ($Player -> authenticate()) {
					// Начинается авторизованная сессия.
					Yii::$app -> user -> login($Player);
					// Регистрация начала сессии текущего пользователя.
					$playerSession = new Session(Yii::$app -> user -> getId());
					$playerSession -> Start();
					// Перенаправление на страницу игры.
					return $this -> redirect(['game/game']);
				}
				// Перенаправление на стартовую страницу.
				return $this -> redirect(Yii::$app -> homeUrl);
			}
		}
		// Отображение страницы регистрации.
		return $this -> render('registration', [
			'Model' => $Model
		]);
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
		$Model = tableUser::findOne(Yii::$app -> user -> getId());
		// Если получены POST данные формы с личными данными:
		if (isset($_POST['User'])) {
			// Если в POST данных отсутствуют пароль и проверочный пароль:
			if ($_POST['User']['Password'] == NULL && $_POST['User']['ControlPassword'] == NULL) {
				// Установка сценария обновления данных пользователя без пароля.
				$Model -> setScenario(tableUser::UPDATE);
				// Удаление из POST данных пустых атрибутов.
				unset($_POST['User']['Password']);
				unset($_POST['User']['ControlPassword']);
				// Установка информации о неизменности пароля для отправки в письме.
				$Password = Yii::t('Dictionary', 'Unchanged');
			}
			else {
				// Установка сценария обновления данных пользователя с паролем.
				$Model -> setScenario(tableUser::UPDATE_PASSWORD);
				// Новый пароль копируется в чистом виде для отправки в письме.
				$Password = $_POST['User']['Password'];
			}

			// AJAX-проверка.
			// $this -> performAjaxValidation($Model);

			// Полученные данные из POST-запроса копируются в модель.
			$Model -> attributes = $_POST['User'];

			//
//			$imageFile = new UploadImage();

			$imageFile = new UploadImage(Yii::$app -> params['uploadedImagesDirectory']);

			// Сохранение файла с изображением и получение нового имени (hash-код) файла.
			$Model -> imageFile = $imageFile -> upload($Model, 'imageFile', true);

			$imageFile -> open(Yii::getAlias(Yii::$app -> params['uploadedImagesDirectory'] . $Model -> imageFile));
			$imageFile -> resize(90, 120);
			$imageFile -> crop(90, 120);
			$imageFile -> save($Model -> imageFile);
			$imageFile -> resize(60, 60);
			$imageFile -> crop(60, 60);
			$imageFile -> save($Model -> imageFile);





			// Если данные пользователя успешно сохранены в БД:
			if ($Model -> save()) {
				$Result = TRUE;
				// Отправка игроку письма об успешном изменении персональных данных.
				$EmailNotification = new EmailNotification($Model -> Email, 'personal', [
					'PlayerName' => $Model -> Name,
					'PlayerEmail' => $Model -> Email,
					'PlayerPassword' => $Password,
				]);
				// Если письмо не отправлено:
				if (!$EmailNotification -> Send())
					// Запись в соответствующий журнал логов сообщения об ошибке.
					Yii::error('Ошибка при отправке пользователю [ ' . $Model -> Email . ' ] письма об изменении личных данных.', 'email');
				// Запись в соответствующий журнал логов информационного сообщения.
				Yii::info('Изменение личных данных пользователя [ ' . $Model -> Email . ' ].', 'user.personal');
			}
		}
		// Удаление из модели пароля и проверочного пароля, 
		// чтобы они не отображались в форме представления.
		$Model -> Password = NULL;
		$Model -> ControlPassword = NULL;
		// Отображение страницы.
		return $this -> render('personal', [
			'Model' => $Model,
			'Result' => $Result
		]);
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

		if (Yii::$app -> request -> isAjax && $Model -> load(Yii::$app -> request -> post())) {
			Yii::$app -> response -> format = Response::FORMAT_JSON;
			return ActiveForm::validate($Model);
		}
	}



	public function actionTest() {

		// $bot = new \app\models\models\User('Bot: Опытный', 'bot9@bot.bot', '12345', 1);
		// print_r($bot);
		// $bot -> Save();


		// $dbModel = tableUser::find()
		// 	-> where(
		// 		'Enable = 1 AND ID <> ' . $this -> ID .
		// 		' AND (ActivityMarker >= (NOW() - INTERVAL ' . $TimeInterval . ' SECOND)' .
		// 		' OR GameMarker >= (NOW() - INTERVAL ' . $TimeInterval . ' SECOND))'
		// 	)
		// 	-> orderBy('Name ASC')
		// 	-> all();

		// $bot = tableUser::find()
		// 	-> where(['id' => 129])
		// 	-> one();

		// $orders = $bot -> getPlayer()
		// 	-> where(['Level' => 1])
		// 	-> all();

		// $bot = tableBot::find()
		// 	-> with('player')
		// 	-> where(['Level' => 1, 'Secret' => 0])
		// 	-> all();

		// $orders = $bot -> getPlayer()
		// 	-> where(['Level' => 1])
		// 	-> all();

		// print_r($bot);

		// echo $bot[0] -> player -> id;

		// $bot = new \app\models\models\Bot();
		// $bot -> Load(132);
		// print_r($bot);

		// echo $bot -> getID() . ' ' . $bot -> getActivityMarker() . ' ' . $bot -> getLevel();

		// $lobby = new \app\models\models\Lobby();
		// $lobby -> set([
		// 	'Name' => 'Тестище!', 
		// 	'SizeX' => 50, 
		// 	'SizeY' => 37, 
		// 	'ColorsNumber' => 4, 
		// 	'PlayersNumber' => 7, 
		// 	'CreatorID' => 1,
		// 	'botsNumber' => 5,
		// 	'botsLevel' => 9
		// ]);
		// $lobby -> Save();
		// $lobby -> Load(1153);
		// $lobby -> set([
		// 	'level' => 3, 
		// 	'botsNumber' => 1
		// ]);
		// $lobby -> Save();
		// print_r($lobby);

		// $dbModel = \app\models\LobbyBot::findOne(null);
		// print_r($dbModel);

		// $dbModel = \app\models\Bot::find()
		// 	-> where(['Level' => 1])
		// 	-> all();
		// print_r($dbModel);

		// $bot = new Bot();
		// $bot -> search(['Level' => 1, 'Secret' => 1]);
		// print_r($bot);

		// $dbModel = \app\models\GameDetail::find()
		// 	-> where(['GameID' => 638])
		// 	-> orderBy('ID DESC')
		// 	-> limit(3)
		// 	-> all();

		// $dbModel = \app\models\GameDetail::find()
		// 	-> where(['GameID' => 638])
		// 	-> orderBy('ID DESC')
		// 	-> limit(4)
		// 	-> all();

		// print_r($dbModel);

		// $game = new \app\models\models\Game();
		// $game -> Load(642, FALSE);
		// echo $game -> getMovesNumber() . '   ';
		// print_r($game-> getMovesList('ASC'));
		// $game -> movesLoad($game -> getMovesList('ASC'));
		// print_r($game);
		// $lobby = new \app\models\models\Lobby();
		// $lobby -> Load(1189);
		// print_r($lobby -> getPlayersList());

		//print_r($this -> ClientDevice);
		//echo !$this -> ClientDevice -> isMobile();
		//echo !$this -> ClientDevice -> isTablet();
//		echo 'mobileDetect '
//			. Yii::$app -> mobileDetect -> isMobile() . ' '
//			. Yii::$app -> mobileDetect -> isTablet() . ' '
//			. Yii::$app -> mobileDetect -> isPhone();

		//echo $this -> ClientDevice -> isMobile() ? 'мобильный' : 'настольный';

//		$this -> view -> theme -> pathMap['@app/views'] = '@app/themes/mobile/views';

//		print_r($this -> view -> theme);

//		$Player = new \app\models\models\Player();
//		$Player -> Load(1);
//		echo $Player -> getID();

//		$FilePath = realpath(Yii::getAlias(Yii::$app -> params['EmailLayout']));
//		die('Путь: ' . $FilePath);




//		$standardWidth = 120;
//		$standardHeight = 160;
//		$standardProportion = $standardWidth / $standardHeight;
//		$startX = 0;
//		$startY = 0;
//
//		$image = \yii\imagine\Image::getImagine() -> open(
//			Yii::getAlias(Yii::$app -> params['uploadedImagesDirectory'] . '79ef908afb3d06893b40d8eb08f40c66.jpg'));
//
//		$size = $image -> getSize();
//
//		echo $proportion = $size -> getWidth() / $size -> getHeight();
//
//		// $width > $height
//		// Обрезка слева и справа.
//		if ($proportion > $standardProportion) {
//			$width = round($standardHeight * $proportion);
//			$box = new \Imagine\Image\Box($width, $standardHeight);
//			$image -> resize($box);
//			$startX = round(($width - $standardWidth) / 2);
//
//		}
//		// $height > $width
//		// Обрезка сверху и снизу.
//		else {
//			$height = round($standardWidth / $proportion);
//			$box = new \Imagine\Image\Box($standardWidth, $height);
//			$image -> resize($box);
//			$startY = round(($height - $standardHeight) / 2);
//
//		}
//
//		$point = new \Imagine\Image\Point($startX, $startY);
//		$box = new \Imagine\Image\Box($standardWidth, $standardHeight);
//
//		print_r($point);
//		print_r($box);
//
//		$image -> crop($point, $box);
//
//		$image -> save(
//			Yii::getAlias(Yii::$app -> params['uploadedImagesDirectory'] . 'image.jpg'));


//		$imageFile = new UploadImage(Yii::$app -> params['uploadedImagesDirectory']);
//		$imageFile -> open(Yii::getAlias(Yii::$app -> params['uploadedImagesDirectory'] . 'c3182e94045ea001beebbb41bac3c725.jpg'));
//		$imageFile -> resize(40, 80);
//		$imageFile -> crop(40, 40);
//		$imageFile -> save('test.jpg');

//		$game = new \app\models\models\Game();
//		print_r($game -> ColorMatrixGeneration(30, 20, 10));


	}

}
