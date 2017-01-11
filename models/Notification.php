<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property string $id
 * @property string $title
 * @property string $message
 * @property string $language
 * @property string $date
 * @property integer $creatorID
 *
 * @property User $creator
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'message', 'creatorID'], 'required'],
            [['date'], 'safe'],
            [['creatorID'], 'integer'],
            [['title'], 'string', 'max' => 25],
            [['message'], 'string', 'max' => 200],
            [['language'], 'string', 'max' => 5],
            [['creatorID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creatorID' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'title' => Yii::t('app', 'title'),
            'message' => Yii::t('app', 'message'),
            'language' => Yii::t('app', 'language'),
            'date' => Yii::t('app', 'date'),
            'creatorID' => Yii::t('app', 'creatorID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creatorID']);
    }
}
