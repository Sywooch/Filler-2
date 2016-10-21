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
     *  Определение типа устройства, как смартфона.
     *  Если тип устройства смартфон, возвращается true, иначе false.
     *
     */
    public function isPhone() {
        if ($this -> isMobile() && !$this -> isTablet())
            return true;
        return false;
    }
    
}
