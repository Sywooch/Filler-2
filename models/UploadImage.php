<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\imagine\Image;

use app\components\SiteException;

use Imagine\Image\Point;
use Imagine\Image\Box;



/**
 *
 *
 */
class UploadImage extends Model {

    /**
     *	Стандартная ширина изображения пользователя.
     *
     */
    const STANDARD_IMAGE_WIDTH = 120;

    /**
     *	Стандартная высота изображения пользователя.
     *
     */
    const STANDARD_IMAGE_HEIGHT = 160;



    /**
     *
     *
     */
    public $directoryPath;

    /**
     *
     *
     */
    public $imageFile;

    /**
     *
     *
     */
    public $startX = 0;

    /**
     *
     *
     */
    public $startY = 0;



    /**
     *
     *
     */
    function __construct($directoryPath) {
//        parent::__construct();
        $this -> directoryPath = $directoryPath;
    }



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
        $directory = Yii::getAlias($this -> directoryPath);
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
     *  Yii::getAlias(Yii::$app->params['uploadedImagesDirectory'] . '79ef908afb3d06893b40d8eb08f40c66.jpg')
     *
     */
    public function open($filePathName) {
        $this -> imageFile = Image::getImagine() -> open($filePathName);
    }

    /**
     *
     *
     */
    public function save($fileName) {
        $this -> imageFile -> save(Yii::getAlias($this -> directoryPath . $fileName));
    }

    /**
     *  $standardProportion = self::STANDARD_IMAGE_WIDTH / self::STANDARD_IMAGE_HEIGHT;
     *
     */
    public function resize($standardWidth, $standardHeight) {
        //
        $standardProportion = $standardWidth / $standardHeight;
        //
        $proportion = $this -> getProportion();
        // $width > $height
        // Обрезка слева и справа.
        if ($proportion > $standardProportion) {
            $width = round($standardHeight * $proportion);
            $imageSize = new Box($width, $standardHeight);
        }
        // $height > $width
        // Обрезка сверху и снизу.
        else {
            $height = round($standardWidth / $proportion);
            $imageSize = new Box($standardWidth, $height);
        }
        //
        $this -> imageFile -> resize($imageSize);
    }

    /**
     *
     *
     */
    public function crop($width, $height) {
        //
        $size = $this -> imageFile -> getSize();

        //
        if ($width > $size -> getWidth() || $height > $size -> getHeight())
            return false;

        // Корректировка новых размеров изображения, если они превышают текущие.
        // $width = $width > $size -> getWidth() ? $size -> getWidth() : $width;
        // $height = $height > $size -> getHeight() ? $size -> getHeight() : $height;
        
        // Вычисление координат начальной точки кадра.
        $startX = round(($size -> getWidth() - $width) / 2);
        $startY = round(($size -> getHeight() - $height) / 2);
        // Координаты начальной точки кадра.
        $startPoint = new Point($startX, $startY);
        // Размер кадра.
        $frameSize = new Box($width, $height);
        // Обрезание изображения по заданным размерам.
        $this -> imageFile -> crop($startPoint, $frameSize);

        return true;
    }

    /**
     *
     *
     */
    protected function getProportion() {
        $size = $this -> imageFile -> getSize();
        return $size -> getWidth() / $size -> getHeight();
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
