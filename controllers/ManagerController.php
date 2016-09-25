<?php

namespace app\controllers;

use Yii;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\helpers\Url;
use yii\filters\VerbFilter;

use app\assets\IndexAsset;
use app\assets\ThemesAsset;

use app\components\ExtController;
use app\components\EmailNotification;
use app\components\UserIdentity;
use app\components\GameException;

// use app\models\User;

use app\models\models\User;
use app\models\models\Player;
use app\models\models\Bot;
use app\models\models\Lobby;
use app\models\models\Game;



/**
 *	
 *
 */
class ManagerController extends ExtController {

	/**
	 *	Макет для представлений контроллера.
	 *
	 */
	const MANAGER_LAYOUT = '/manager';

	/**
	 *	Код ошибки данных.
	 *
	 */
	const DATA_ERROR = 'DATA_ERROR';

	/**
	 *	Код ошибки.
	 *
	 */
	const ERROR = 0;
	


	/**
	 *	Макет для представлений контроллера.
	 *
	 */
	public $layout = self::MANAGER_LAYOUT;



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
				'only' => ['dashboard',],
				// Описание правил доступа.
				'rules' => [
					// Список действий, доступных не авторизованным пользователям.
					[
						'actions' => [
							'dashboard',
						],
						'allow' => true,
						'roles' => ['?'],
					],
					// Список действий, доступных авторизованным пользователям.
					[
						'actions' => [
							'dashboard',
						],
						'allow' => true,
						'roles' => ['@'],
					]
				],
			],
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
	// 		'ajaxOnly + MoveSet',
	// 		'ajaxOnly + MoveGet',
	// 		// Фильтр контроля доступа.
	// 		'accessControl'
	// 	);
	// }



	/**
	 *	Возвращает диалоговые сообщения (заголовок, текст, кнопки, индикатор загрузки).
	 *
	 */
	private function getDialogMessages() {
		// // Получение пути к файлу с набором шаблонов.
		// $FilePath = realpath(Yii::$app -> params['DialogLayout']);
		// // Если файл с набором шаблонов не найден:
		// if ($FilePath === false)
		// 	throw new GameException('Не удается открыть файл с набором шаблонов диалоговых окон.');
		// // Получение набора шаблонов диалоговых окон.
		// $DialogLayout = require($FilePath);
		// return $DialogLayout;
	}



	/**
	 *	
	 *
	 */
	public function actionDashboard() {
		// Получение из запроса идентификатора пользователя.
		$userID = Yii::$app -> request -> post('userID');
		$user = new User();
		// Вывод представления.
		return $this -> render('dashboard', [
			'userID' => Yii::$app -> user -> getId()
		]);
	}

}
