<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "session".
 *
 * @property string $id
 * @property string $UserIP
 * @property string $UserAgent
 * @property integer $DeviceType
 * @property string $StartDate
 * @property string $EndDate
 * @property string $ActivityMarker
 * @property string $GameMarker
 * @property integer $UserID
 *
 * @property User $user
 */
class Session extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DeviceType', 'UserID'], 'integer'],
            [['StartDate', 'EndDate', 'ActivityMarker', 'GameMarker'], 'safe'],
            [['UserIP'], 'string', 'max' => 50],
            [['UserAgent'], 'string', 'max' => 200],
            [['UserID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['UserID' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'UserIP' => Yii::t('app', 'UserIP'),
            'UserAgent' => Yii::t('app', 'UserAgent'),
            'DeviceType' => Yii::t('app', 'DeviceType'),
            'StartDate' => Yii::t('app', 'StartDate'),
            'EndDate' => Yii::t('app', 'EndDate'),
            'ActivityMarker' => Yii::t('app', 'ActivityMarker'),
            'GameMarker' => Yii::t('app', 'GameMarker'),
            'UserID' => Yii::t('app', 'UserID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'UserID']);
    }
}
