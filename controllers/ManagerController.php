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

use app\models\models\User;
use app\models\models\Map;



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



	/**
	 *
	 *
	 */
	public function actionMap() {
		// Получение из запроса идентификатора пользователя.
		$userID = Yii::$app -> request -> post('userID');
		$user = new User();

		// Передача скрипту данных собственного игрока, диалогов и информации по загрузке игры.
		Yii::$app -> view -> registerJs(
			"var BASE_URL = '" . Yii::$app -> request -> baseUrl . "';
			var DIALOG = " . json_encode($this -> getDialogMessages()) . ";",
			yii\web\View::POS_HEAD
		);
		
		// Вывод представления.
		return $this -> render('map', [
			'userID' => Yii::$app -> user -> getId()
		]);
	}



	/**
	 *	Возвращает диалоговые сообщения (заголовок, текст, кнопки, индикатор загрузки).
	 *
	 */
	private function getDialogMessages() {
		// Получение пути к файлу с набором шаблонов.
		$FilePath = realpath(Yii::getAlias(Yii::$app -> params['DialogLayout']));
		// Если файл с набором шаблонов не найден:
		if ($FilePath === false)
			throw new GameException('Не удается открыть файл с набором шаблонов диалоговых окон.');
		// Получение набора шаблонов диалоговых окон.
		$DialogLayout = require($FilePath);
		return $DialogLayout;
	}



	/**
	 *	Работает в формате AJAX-запросов от клиента.
	 *
	 *
	 */
	public function actionMapsave() {
		// Получение из запроса идентификаторов игры и игрока, индекса цвета и количества ячеек.
		$name = Yii::$app -> request -> post('name');
		$matrix = Yii::$app -> request -> post('matrix');
		$sizeX = Yii::$app -> request -> post('sizeX');
		$sizeY = Yii::$app -> request -> post('sizeY');
		$description = Yii::$app -> request -> post('description');
		$comment = Yii::$app -> request -> post('comment');		
		$enable = Yii::$app -> request -> post('enable');
		$gameMap = new Map();
		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			$gameMap -> set([
				'name' => $name,
				'matrix' => json_decode($matrix),
				'sizeX' => $sizeX,
				'sizeY' => $sizeY,
				'description' => $description,
				'comment' => $comment,
				'enable' => $enable
			]);
			// Если указанная игра найдена и ход успешно зарегистрирован:
			if ($gameMap -> Save())
				// Возвращает Error = false.
				echo(json_encode(['error' => false]));
			// Если возникла ошибка:
			else
				echo(json_encode(['error' => self::DATA_ERROR]));
			Yii::$app -> end();
		}
		else {
			// $this -> render();
		}
	}



	/**
	 *	Работает в формате AJAX-запросов от клиента.
	 *
	 *
	 */
	public function actionMaplistload() {
		//
		$type = Yii::$app -> request -> post('type');
		$sizeX = Yii::$app -> request -> post('sizeX');
		$sizeY = Yii::$app -> request -> post('sizeY');

		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			$size = [];
			if (0) {
				$size = [
					'sizeX' => $sizeX,
					'sizeY' => $sizeY
				];
			}

			$dbModel = \app\models\Map::find()
				-> where($size)
				-> orderBy('name')
				-> all();

			//
			if ($dbModel) {
				$mapList = [];
				// Формирование списка активных лобби.
				foreach ($dbModel as $mapData) {
					$mapList[] = [
						'id' => $mapData['id'],
						'name' => $mapData['name']
					];
				}
				// Возвращается список действующих лобби.
				echo(json_encode($mapList));
			}
			// Если игрок не найден:
			else
				// Возвращается код ошибки.
				echo(json_encode(['Error' => self::DATA_ERROR]));
			Yii::$app -> end();
		}
		else {
			// $this -> render();
		}
	}



	/**
	 *	Работает в формате AJAX-запросов от клиента.
	 *	
	 *
	 */
	public function actionMapload() {
		// Получение из запроса идентификатора лобби.
		$mapID = Yii::$app -> request -> post('mapID');
		$map = new Map();
		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			// Если указанное лобби найдено:
			if ($map -> Load($mapID))
				// Возвращает информацию о лобби.
				echo(json_encode($map -> getPropertyList()));
			// Если возникла ошибка:
			else
				// Возвращает код ошибки.
				echo(json_encode(['Error' => self::DATA_ERROR]));
			Yii::$app -> end();
		}
		else {
			// $this -> render();
		}
	}

}
