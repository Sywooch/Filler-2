<?php

namespace app\components;



/**
 * ExtMobileDetect class file.
 *
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 * @copyright 2016 Konstantin Poluektov
 *
 */



/**
 * ExtMobileDetect detects the type of phone.
 *
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 *
 */
class ExtMobileDetect extends \skeeks\yii2\mobiledetect\MobileDetect {

    /**
     *	Тип устройства: Компьютер
     *
     */
    const DESKTOP_DEVICE = 1;

    /**
     *	Тип устройства: Планшет
     *
     */
    const TABLET_DEVICE = 2;

    /**
     *	Тип устройства: Смартфон
     *
     */
    const PHONE_DEVICE = 3;
    
    

    /**
     *  Определение типа устройства, как смартфона.
     *  Если тип устройства смартфон, возвращается true, иначе false.
     *
     */
    public function isPhone() {
        if ($this -> isMobile() && !$this -> isTablet())
            return true;
        return false;
    }



    /**
     *  Возвращает тип устройства.
     *
     */
    public function getDeviceType() {
        //
        if ($this -> isPhone())
            return self::PHONE_DEVICE;
        elseif ($this -> isTablet())
            return self::TABLET_DEVICE;
        else
            return self::DESKTOP_DEVICE;
    }

}
