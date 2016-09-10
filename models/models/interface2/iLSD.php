<?php

namespace app\models\models\interface2;

/**
 *	iLSD - интерфейс Load (Загрузка) / Save (Сохранение) / Delete (Удаление).
 *	Используется для реализации работы с базой данных.
 *
 */
interface iLSD {
	public function Load($ID);
	public function Save();
	public function Delete();
}
