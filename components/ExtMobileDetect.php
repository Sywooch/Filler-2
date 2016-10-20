<?php

namespace app\components;



/**
 *  
 *
 */
class ExtMobileDetect extends \skeeks\yii2\mobiledetect\MobileDetect {

    /**
     *  
     *
     */
    public function isPhone() {
        if ($this -> isMobile() && !$this -> isTablet())
            return true;
        return false;
    }
    
}
