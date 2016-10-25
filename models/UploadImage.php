<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

use app\components\SiteException;



/**
 *
 *
 */
class UploadImage extends Model {

    /**
     *
     *
     */
    public $imageFile;

    /**
     *
     *
     */
    public function rules() {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     *
     *
     */
    public function upload($model, $attribute, $fileName = false) {
        // Получение пути к директории хранения файла.
        $directory = Yii::getAlias(Yii::$app -> params['uploadedImagesDirectory']);
        // Получение образца файла.
        $this -> imageFile = UploadedFile::getInstance($model, $attribute);
        // Если требуется переименование файла:
        if ($fileName)
            $this -> imageFile -> name = $this -> getFileName();
        // Если файл соответствует требованиям:
        if ($this -> validate()) {
            // Если файл успешно сохранен:
            if ($this -> imageFile -> saveAs($directory . $this -> imageFile -> name))
                // Возвращается имя сохраненного файла.
                return $this -> imageFile -> name;
            return null;
        }
        else
            return null;
    }

    /**
     *
     *
     */
    protected function getFileName() {
        if (!$this -> imageFile instanceof UploadedFile)
            throw new SiteException('Свойство не является объектом класса UploadedFile.');
        // Возвращается новое имя файла.
        return md5($this -> imageFile -> name) . '.' . $this -> imageFile -> getExtension();
    }
}
