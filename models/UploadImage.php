<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;



class UploadImage extends Model {

    public $imageFile;

    public function rules() {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function upload() {
        if ($this -> validate()) {
            $directory = Yii::getAlias(Yii::$app -> params['uploadedImagesDirectory']);
            $this -> imageFile = UploadedFile::getInstance($this, 'imageFile');
            $this -> imageFile -> name = md5($this -> imageFile -> name) . '.' . $this -> imageFile -> getExtension();
            if ($this -> imageFile -> saveAs($directory . $file -> name))
                return true;
            return false;
        }
        else {
            return false;
        }
    }
}
