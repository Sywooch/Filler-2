<?php

/**
 * EmailNotification class file.
 *
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 * @copyright 2016 Konstantin Poluektov
 *
 */



/**
 * EmailNotification manages the sending of emails.
 *
 * @property string $Name The name of the sender.
 * @property string $Subject The subject of the email.
 * @property string $Email The recipient's address.
 * @property string $Message The text of the message.
 * @property string $Signature The signature of the email.
 *
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 *
 */
class EmailNotification {

	/**
	 *	Имя отправителя.
	 *
	 */
	protected $Name;

	/**
	 *	Тема письма.
	 *
	 */
	protected $Subject;

	/**
	 *	Адрес получателя.
	 *
	 */
	protected $Email;

	/**
	 *	Текст сообщения.
	 *
	 */
	protected $Message;

	/**
	 *	Подпись письма.
	 *
	 */
	protected $Signature;



	/**
	 *	Подготовка письма по указанному шаблону.
	 *
	 */
	function __construct($Email, $LayoutName, $Parameters = array()) {
		// Электронная почта получателя.
		$this -> Email = $Email;
		// Получение пути к файлу с набором шаблонов.
		$FilePath = realpath(Yii::app() -> params['EmailLayout']);
		// Если файл с набором шаблонов не найден:
		if ($FilePath === false)
			throw new GameException('Не удается открыть файл с набором шаблонов электронных сообщений.');
		// Получение набора шаблонов электронных сообщений.
		$EmailLayout = require($FilePath);
		// Получение из указанного шаблона имени отправителя.
		$this -> Name = $EmailLayout[$LayoutName]['name'];
		// Получение из указанного шаблона темы письма.
		$this -> Subject = $EmailLayout[$LayoutName]['subject'];
		// Получение из указанного шаблона текста сообщения.
		$this -> Message = $EmailLayout[$LayoutName]['message'];
		// Вставка в текст сообщения всех необходимых данных.
		while (strpos($this -> Message, '{') !== false) {
			// 
			$Begin = strpos($this -> Message, '{');
			// 
			$End = strpos($this -> Message, '}');
			// 
			$ParameterName = substr($this -> Message, $Begin + 1, $End - $Begin - 1);
			// Если параметр получен:
			if (isset($Parameters[$ParameterName]))
				// 
				$this -> Message = str_replace('{' . $ParameterName . '}', $Parameters[$ParameterName], $this -> Message);
			// Если параметр не задан:
			else
				// Генерирование исключения.
				throw new Exception('Не задан параметр ' . $ParameterName);
		}
		// Получение из указанного шаблона подписи письма.
		$this -> Signature = $EmailLayout[$LayoutName]['signature'];
	}



	/**
	 *	Формирование и отправка письма.
	 *
	 */
	public function Send() {
		// Имя отправителя письма.
		$Name = '=?UTF-8?B?' . base64_encode($this -> Name) . '?=';
		// Тема письма.
		$Subject = '=?UTF-8?B?' . base64_encode($this -> Subject) . '?=';
		// Настройка заголовков письма.
		$Headers = "From: " . $Name . " <" . Yii::app() -> params['GameEmail'] . ">\r\n" .
			"Reply-To: " . $Name . " <" . Yii::app() -> params['GameEmail'] . ">\r\n" .
			"MIME-Version: 1.0\r\n" .
			"Content-Type: text/plain; charset=UTF-8";
		// echo $this -> Message . $this -> Signature;
		// Отправка подготовленного письма пользователю.
		return mail($this -> Email, $Subject, $this -> Message . $this -> Signature, $Headers);
	}
	
}
