<?php

/**
 *	Шаблоны электронных писем.
 *
 */
return [

	// Регистрация пользователя
	'registration' => [
		// 
		'name' => Yii::t('Dictionary', 'Filler'),
		// 
		'subject' => Yii::t('Dictionary', 'Registration'),
		// 
		'message' => Yii::t('Dictionary', 'Thank you for registering in the online game Filler!') . PHP_EOL . PHP_EOL . 
			Yii::t('Dictionary', 'Your registration data') . ':' . PHP_EOL . PHP_EOL . 
			Yii::t('Dictionary', 'Player name') . ': {PlayerName}' . PHP_EOL . 
			Yii::t('Dictionary', 'E-mail') . ': {PlayerEmail}' . PHP_EOL . 
			Yii::t('Dictionary', 'Password') . ': {PlayerPassword}',
		// 
		'signature' => PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . Yii::t('Dictionary', 'Good luck!') . PHP_EOL . PHP_EOL . '----------' . 
			PHP_EOL . Yii::t('Dictionary', 'Filler - Multiplayer online game'),
	],

	// Изменение данных пользователя
	'personal' => [
		// 
		'name' => Yii::t('Dictionary', 'Filler'),
		// 
		'subject' => Yii::t('Dictionary', 'Change of personal data'),
		// 
		'message' => Yii::t('Dictionary', 'You have changed personal data in the online game Filler.') . PHP_EOL . PHP_EOL . 
			Yii::t('Dictionary', 'Your new personal data') . ':' . PHP_EOL . PHP_EOL . 
			Yii::t('Dictionary', 'Player name') . ': {PlayerName}' . PHP_EOL . 
			Yii::t('Dictionary', 'E-mail') . ': {PlayerEmail}' . PHP_EOL . 
			Yii::t('Dictionary', 'Password') . ': {PlayerPassword}',
		// 
		'signature' => PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . Yii::t('Dictionary', 'Good luck!') . PHP_EOL . PHP_EOL . '----------' . 
			PHP_EOL . Yii::t('Dictionary', 'Filler - Multiplayer online game'),
	],

	// Восстановление доступа : Шаг 1 из 2
	'forgot' => [
		// 
		'name' => Yii::t('Dictionary', 'Filler'),
		// 
		'subject' => Yii::t('Dictionary', 'Access recovery'),
		// 
		'message' => Yii::t('Dictionary', 'Someone requested access recovery for account {PlayerEmail} in the online game Filler.') . PHP_EOL . 
			Yii::t('Dictionary', 'If you did it, just click on the following link to set a new password (the link expire after 1 hour):') . 
			PHP_EOL . PHP_EOL . '{Link}' . PHP_EOL . PHP_EOL . 
			Yii::t('Dictionary', 'If you change your mind or this request was made by someone else, just ignore this email or delete it.'),
		// 
		'signature' => PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . Yii::t('Dictionary', 'Good luck!') . PHP_EOL . PHP_EOL . '----------' . 
			PHP_EOL . Yii::t('Dictionary', 'Filler - Multiplayer online game'),
	],

	// Восстановление доступа : Шаг 2 из 2
	'recovery' => [
		// 
		'name' => Yii::t('Dictionary', 'Filler'),
		// 
		'subject' => Yii::t('Dictionary', 'Access recovery'),
		// 
		'message' => Yii::t('Dictionary', 'Access your account in the online game Filler restored successfully!') . PHP_EOL . PHP_EOL . 
			Yii::t('Dictionary', 'Your new personal data') . ':' . PHP_EOL . PHP_EOL . 
			Yii::t('Dictionary', 'Player name') . ': {PlayerName}' . PHP_EOL . 
			Yii::t('Dictionary', 'E-mail') . ': {PlayerEmail}' . PHP_EOL . 
			Yii::t('Dictionary', 'Password') . ': {PlayerPassword}',
		// 
		'signature' => PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . Yii::t('Dictionary', 'Good luck!') . PHP_EOL . PHP_EOL . '----------' . 
			PHP_EOL . Yii::t('Dictionary', 'Filler - Multiplayer online game'),
	],

];
