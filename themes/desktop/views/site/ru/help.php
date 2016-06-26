
<div class="row">
	<div class="col-xs-1">
	</div>
	<div class="col-xs-22">
		<div class="col-xs-24 text-left indent-top-md indent-bottom-md text-18 color-blue">
			Помощь
		</div>
		<div class="col-sm-11 col-xs-24 text-14 color-gray">
			<span class="title-2">ОБ ИГРЕ</span>
			<p>
			Филлер - бесплатная логическая многопользовательская онлайн игра.
			</p>
			<p>
			Цель игры - захватить как можно больше ячеек и заработать как можно больше баллов.
			</p>
			<p>
			Одновременно могут играть 2 или 4 игрока. 
			Для того, чтобы стать игроком, необходимо зарегистрироваться.
			</p>

			<span class="title-2">РЕГИСТРАЦИЯ</span>
			<p>
			Процедура регистрации очень простая. 
			Необходимо в разделе «Регистрация» ввести требуемые данные: имя игрока, электронную почту и пароль. 
			В дальнейшем, для авторизации, необходимо будет вводить электронную почту и пароль. 
			</p>
			<p>
			Вся регистрационная информация отправляется на электронную почту пользователя.
			</p>

			<span class="title-2">ИЗМЕНЕНИЕ ЛИЧНЫХ ДАННЫХ</span>
			<p>
			При необходимости вы всегда можете изменить личные данные (имя игрока, электронная почта, пароль). 
			Для этого необходимо в авторизованном режиме открыть раздел «Личные данные».
			</p>
			<p>
			Измененные личные данные отправляются на электронную почту пользователя.
			</p>

			<span class="title-2">ВОССТАНОВЛЕНИЕ ПАРОЛЯ</span>
			<p>
			Если вы забыли пароль, можно воспользоваться процедурой восстановления пароля. 
			Для этого в окне авторизации необходимо пройти по ссылке «Забыли пароль?» и ввести 
			адрес электронной почты, которую вы указывали при регистрации.
			</p>
			<p>
			На электронную почту пользователя будет отправлено письмо со ссылкой для дальнейших действий.
			</p>

			<span class="title-2">ИЗМЕНЕНИЕ ЯЗЫКА</span>
			<p>
			Игра поддерживает несколько языков. 
			Для изменения языка достаточно просто нажать на соответствующую кнопку в правом верхнем углу экрана. 
			</p>
		</div>
		<div class="col-sm-2 hidden-xs"></div>
		<div class="col-sm-11 col-xs-24 text-14 color-gray">
			<span class="title-2">СОЗДАНИЕ ЛОББИ</span>
			<p>
			Для того, чтобы собрать игроков и начать игру необходимо создать лобби. 
			В лобби необходимо ввести название, выбрать количество игроков, размер игрового поля и количество цветов.
			</p>
			<p>
			После публикации лобби, включается режим ожидания подключения игроков. 
			Как только все игроки подключатся, можно начинать игру.
			</p>
			<p>
			Если до истечения срока действия лобби необходимое количество игроков не наберется, 
			лобби будет аннулировано.
			</p>
			<p>
			Игрок также может не создавать лобби, а подключиться к одному из уже созданных другими игроками.
			</p>

			<span class="title-2">ИГРОВОЙ ПРОЦЕСС</span>
			<p>
			Каждый игрок начинает игру из домашней ячейки, расположение которой специально обозначено. 
			Домашние ячейки всех игроков имеют разный цвет. 
			Игроки делают ходы по очереди. Первый ход делает игрок, создавший лобби. 
			</p>
			<p>
			Для того, чтобы сделать ход, необходимо выбрать образец цвета под игровым полем. 
			При этом все ячейки, примыкающие к территории игрока и имеющие цвет, совпадающий с выбранным, 
			будут захвачены игроком. 
			Вся территория игрока перекрашивается в выбранный цвет.
			</p>
			<p>
			Во время хода нельзя выбрать цвет, уже принадлежащий другому игроку.
			</p>
			<p>
			Игроку отводится ограниченное количество времени, чтобы сделать ход. 
			Если игрок не успевает сделать ход, ход делается автоматически и переходит к другому игроку.
			</p>
			<p>
			Игра заканчивается, как только один из игроков захватит большую часть ячеек, чем любой другой.
			</p>

			<span class="title-2">ПРЕРЫВАНИЕ ИГРЫ</span>
			<p>
			Во время игры на непродолжительное время игрок может переходить в другие разделы (личные данные и т.п.), 
			переключать язык или даже перезагрузить браузер или компьютер. 
			</p>
			<p>
			При возвращении в игровой раздел незавершенная игра будет автоматически восстановлена, при условии, что 
			не превышен лимит времени отсутствия игрока. 
			</p>
			<p>
			В случае превышения лимита времени игра будет остановлена для всех игроков.
			</p>

			<span class="title-2">СТАТИСТИКА</span>
			<p>
			По каждому игроку ведется статистика основных показателей: общее количество игр и количество побед, 
			рейтинг, непрерывная победная серия.
			</p>
		</div>
	</div>
	<div class="col-xs-1">
	</div>
</div>

<div class="row">
	<div class="col-md-24 IndexFooterMenuBlock ">

<?php

	// Если пользователь не авторизован:
	if (Yii::app() -> user -> isGuest) {
		// Выводится меню "В начало".
		$this -> widget('ext.FooterMenu.FooterMenuWidget', array(
			'ItemList' => array(
				Yii::t('Dictionary', 'Start') => $this -> createUrl("/site/index")
			)
		));
	}
	// Если пользователь авторизован:
	else {
		// Выводится меню "Играть | Выход".
		$this -> widget('ext.FooterMenu.FooterMenuWidget', array(
			'ItemList' => array(
				Yii::t('Dictionary', 'Play') => $this -> createUrl("/game/game"),
				Yii::t('Dictionary', 'Logout') => $this -> createUrl("/site/logout")
			),
			'Style' => 2
		));
	}

?>

	</div>
</div>