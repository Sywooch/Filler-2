<?php

namespace app\controllers;

use Yii;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\helpers\Url;
use yii\filters\VerbFilter;

use app\components\ExtController;
use app\components\EmailNotification;
use app\components\UserIdentity;
use app\components\GameException;

use app\models\models\User;



/**
 *	
 *
 */
class NotificationController extends ExtController {

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
	 *	Работает в формате AJAX-запросов от клиента.
	 *
	 *
	 */
	public function actionMapsave() {
		// Получение из запроса идентификаторов игры и игрока, индекса цвета и количества ячеек.
		$mapPropertyList = Yii::$app -> request -> post();
		// Преобразование матрицы из формата JSON в массив.
		$mapPropertyList['matrix'] = json_decode($mapPropertyList['matrix']);
		//
		$gameMap = new Map();
		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			// Загрузка полученных параметров карты в модель карты.
			$gameMap -> set($mapPropertyList);
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
			$filter = [];
			if ($sizeX && $sizeY) {
				$filter = [
					'sizeX' => $sizeX,
					'sizeY' => $sizeY
				];
			}
			if ($type)
				$filter['type'] = $type;

			$dbModel = \app\models\Map::find()
				-> where($filter)
				-> orderBy('name')
				-> all();

			//
			if ($dbModel) {
				$mapList = [];
				// Формирование списка карт.
				foreach ($dbModel as $mapData) {
					$mapList[] = [
						'id' => $mapData['id'],
						'name' => $mapData['name'],
						'sizeX' => $mapData['sizeX'],
						'sizeY' => $mapData['sizeY'],
						'description' => $mapData['description'],
						'type' => $mapData['type'],
						'comment' => $mapData['comment'],
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
	public function actionNotificationget() {
		// Получение из запроса идентификатора лобби.
		$playerID = Yii::$app -> request -> post('PlayerID');
//		$map = new Map();
		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			// Если указанное лобби найдено:
			if (1) {
				$notification = [];
				$notification[] = [
					'Caption' => Yii::t('Dictionary', 'Обновления игры 1 из 3'),
					'Message' => Yii::t('Dictionary', 'Добавлена возможность осуществлять ход кликом по ячейке на игровом поле.'),
					'YesButton' => Yii::t('Dictionary', 'Далее'),
					'NoButton' => '',
					'Type' => 'info',
					'Format' => 'Notification',
					'Loading' => false
				];
				$notification[] = [
					'Caption' => Yii::t('Dictionary', 'Обновления игры 2 из 3'),
					'Message' => Yii::t('Dictionary', 'Устранены недочеты, возникающие при создании лобби.'),
					'YesButton' => Yii::t('Dictionary', 'Далее'),
					'NoButton' => '',
					'Type' => 'info',
					'Format' => 'Notification',
					'Loading' => false
				];
				$notification[] = [
					'Caption' => Yii::t('Dictionary', 'Обновления игры 3 из 3'),
					'Message' => Yii::t('Dictionary', 'Интерфейс игры стал еще более красочным и удобным.'),
					'YesButton' => '',
					'NoButton' => Yii::t('Dictionary', 'Закрыть'),
					'Type' => 'info',
					'Format' => 'Notification',
					'Loading' => false
				];
				// Возвращает информацию о лобби.
				echo(json_encode($notification));
			}
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
