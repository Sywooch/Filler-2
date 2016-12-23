<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "map".
 *
 * @property integer $id
 * @property string $matrix
 * @property integer $sizeX
 * @property integer $sizeY
 * @property string $name
 * @property string $description
 * @property string $comment
 * @property integer $enable
 */
class Map extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'map';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['matrix', 'sizeX', 'sizeY', 'name'], 'required'],
            [['matrix'], 'string'],
            [['sizeX', 'sizeY', 'enable'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 200],
            [['comment'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'matrix' => Yii::t('app', 'matrix'),
            'sizeX' => Yii::t('app', 'sizeX'),
            'sizeY' => Yii::t('app', 'sizeY'),
            'name' => Yii::t('app', 'name'),
            'description' => Yii::t('app', 'description'),
            'comment' => Yii::t('app', 'comment'),
            'enable' => Yii::t('app', 'enable'),
        ];
    }
}
