<?php

/**
 *	Контроллер идентификации пользователя.
 *	Выполняет авторизацию пользователя. Сообщает об ошибках при авторизации.
 *	Предоставляет идентификатор авторизованного пользователя.
 *
 */
class UserIdentity extends CUserIdentity {

	/**
	 *	Идентификатор авторизованного пользователя.
	 *
	 */
	private $ID;



	/**
	 *	Выполняет авторизацию пользователя.
	 *	Ищет в БД пользователя с указанной электронной почтой.
	 *
	 *		Если пользователь найден, проверяет правильность указанного пароля.
	 *
	 *			Если пароль правильный, устанавливается идентификатор пользователя, 
	 *			сбрасывается код и описание ошибки.
	 *			Возвращает 1.
	 *
	 *			Если пароль не правильный, устанавливается код ошибки ERROR_USERNAME_INVALID.
	 *			Возвращает 0.
	 *
	 *		Если пользователь не найден, устанавливается код ошибки ERROR_USERNAME_INVALID.
	 *		Возвращает 0.
	 *
	 */
	public function authenticate() {
		// Поиск в БД пользователя с указанной электронной почтой.
		$Model = tableUser::model() -> findByAttributes(array('Email' => $this -> username));
		// Если пользователь не найден в БД:
		if ($Model === null) {
			// Устанавливается код и описание ошибки.
			$this -> errorCode = self::ERROR_USERNAME_INVALID;
			$this -> errorMessage = Yii::t('Dictionary', 'Incorrect e-mail address or password');
		}
		// Если пользователь найден, но полученный пароль не соответствует:
		else if (!CPasswordHelper::verifyPassword($this -> password, $Model -> Password)) {
			// Устанавливается код и описание ошибки.
			$this -> errorCode = self::ERROR_USERNAME_INVALID;
			$this -> errorMessage = Yii::t('Dictionary', 'Incorrect e-mail address or password');
		}
		// Если пользователь найден и пароль верный:
		else {
			// Устанавливается идентификатор пользователя.
			$this -> ID = $Model -> ID;
			// Сброс кода и описания ошибки.
			$this -> errorCode = self::ERROR_NONE;
			$this -> errorMessage = '';
			return true;
		}
		return !$this -> errorCode;
	}



	/**
	 *	Возвращает идентификатор авторизованного пользователя.
	 *
	 */
	public function getId() {
		return $this -> ID;
	}

}
