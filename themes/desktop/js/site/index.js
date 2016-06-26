
/**
 *	Вывод окна авторизации пользователя.
 *
 */
function LoginWindow() {
	$('#PlayerAuthorization').modal('show');
}



/**
 *	Вывод ошибки.
 *
 */
function ErrorSet(ErrorMessage) {
	$('#AuthorizationError').html(ErrorMessage);
	$('#AuthorizationError').css({'display': 'block'});
	$('.form-group').addClass('has-error');
}



/**
 *	Удаление ошибки.
 *
 */
function ErrorReset() {
	if ($('#AuthorizationError').html() != '')
		$('.form-group').removeClass('has-error');
	$('#AuthorizationError').html('');
}



$(document).ready(function () {
	// Предварительная валидация данных (электронная почта и пароль),
	// отправка AJAX-запроса для авторизации пользователя,
	// вывод информации об ошибках, 
	// переадресация авторизованного пользователя.
	$('form').validate({
		// Настройка правил валидации для формы авторизации.
		rules: {
			Email: {
				email: true,
				required: true
			},
			Password: {
				required: true
			}
		},
		// Сообщения об ошибках ввода данных.
		messages: {
			Email: {
				required: ERROR_MESSAGE[0],
				email: ERROR_MESSAGE[2]
			},
			Password: {
				required: ERROR_MESSAGE[1]
			}
		},
		highlight: function(element, errorClass) {
			$(element).add($(element).parent()).addClass('has-error');
		},
		unhighlight: function(element, errorClass) {
			$(element).add($(element).parent()).removeClass('has-error');
		},
		errorElement: 'div',
		errorClass: 'error-message text-14',
		// Указание места вывода ошибок над соответствующими полями ввода.
		errorPlacement: function(error, element) {
			error.prependTo(element.parent('div'));
		},
		submitHandler: function() {
			// Включение индикатора загрузки данных.
			$('#Loading').show();
			$('form').ajaxSubmit({
				type: 'POST',
				url: BASE_URL + '/site/login',
				dataType: 'json',
				// При успешной передаче AJAX-запроса:
				success: function(data) {
					// Если авторизация пройдена успешно:
					if (data == 1)
						// Переадресация в раздел Игра.
						window.location.href = BASE_URL + '/game/game';
					// Если авторизация не пройдена:
					else {
						// Выключение индикатора загрузки данных.
						$('#Loading').hide();
						// Сообщение об ошибке выводится над полем Электронная почта.
						ErrorSet(data.ErrorMessage);
					}
				},
				// При возникновении ошибки в AJAX-запросе:
				error: function(jqXHR, textStatus, errorThrown) {
					// Выключение индикатора загрузки данных.
					$('#Loading').hide();
				}
			});
		}
	});

	// Удаление сообщения об ошибке при нажатии клавиши в поле Электронная почта.
	$('#Email').keydown(function () {
		ErrorReset();
	});

	// Удаление сообщения об ошибке при нажатии клавиши в поле Пароль.
	$('#Password').keydown(function () {
		ErrorReset();
	});

});
