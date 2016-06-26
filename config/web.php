<?php

$params = require(__DIR__ . '/params.php');

$config = [
	'id' => 'filler',
	// 'layout' => 'index',
	'basePath' => dirname(__DIR__),
	'defaultRoute' => 'site/index',
	// 'sourceLanguage' => 'en',
	'language' => 'ru-RU',
	// 'bootstrap' => ['log'],
	'bootstrap' => ['debug'],
	'modules' => [
		'debug' => [
			'class' => 'yii\debug\Module',
		],
	],
	'components' => [
		'request' => [
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => 'wRA_igcLeSVOk_UAqJoXfcQYcfF_Y_8O',
			// 'baseUrl' => '',
		],
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'user' => [
			'identityClass' => 'app\models\User',
			'enableAutoLogin' => true,
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.
			'useFileTransport' => true,
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'db' => require(__DIR__ . '/db.php'),
		// 
		'i18n' => [
			'translations' => [
				'*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@app/messages',
					'sourceLanguage' => 'en-US',
					// 'fileMap' => [
					// 	'app'       => 'app.php',
					// 	'app/error' => 'error.php',
					// ],
				],
			],
		],
		
		'urlManager' => [
			// 'class' => 'app\components\ExtUrlManager',
			'class' => 'yii\web\UrlManager',
			// 'urlFormat' => 'path',
			'enablePrettyUrl' => true,
			'enableStrictParsing' => false,
			'showScriptName' => false,
			'rules' => [
				// 'filler/web/' => 'site/index',
				// 'posts' => 'site/index',
				'<language:(ru-RU|en-EN)>/<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<language:(ru-RU|en-EN)>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<language:(ru-RU|en-EN)>/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			],
		],
		
		'view' => [
			'theme' => [
				// 'basePath' => '@webroot/themes/desktop/views',
				// 'baseUrl' => '@web/themes/desktop/views',
				// 'pathMap' => [
				// 	'@app/views' => '@webroot/themes/desktop/views',
				// ],
				'basePath' => '@app/themes/desktop',
				'baseUrl' => '@web/themes/desktop',
				'pathMap' => [
					'@app/views' => '@app/themes/desktop/views',
				],
			],

			// 'theme' => [
			// 	// 'class'=>'app\components\Theme',
			// 	'basePath' => '@app/themes/desktop/views',
			// 	'baseUrl' => '@web/themes/desktop/views',
			// 	// 'active'=>'desktop',
			// 	'pathMap' => [
			// 		'desktop' => [
			// 			'@app/views' => ['@app/themes/desktop/views']
			// 		],
			// 		'mobile' => [
			// 			'@app/views' => ['@app/themes/mobile/views']
			// 		],
			// 	],
			// ],
		],
	],
	'params' => $params,
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class' => 'yii\debug\Module',
	];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
		/*
		'generators' => [
			'model' => [  
				'class' => 'yii\gii\generators\models\Generator', // generator class
				'templates' => [  
					'myModelTpl' => '@app/gii/modeltpl', // template name => path to template
				]
			],
			'crud'	 => [
				'class'		=> 'yii\gii\generators\crud\Generator',
				'templates' => ['mycrud' => '@app/templates/mycrud']
			]
		]
		*/
	];
}

return $config;