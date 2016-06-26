<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recovery".
 *
 * @property string $ID
 * @property string $Code
 * @property string $Date
 * @property integer $UserID
 * @property integer $Status
 *
 * @property User $user
 */
class Recovery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'recovery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Code', 'UserID'], 'required'],
            [['Date'], 'safe'],
            [['UserID', 'Status'], 'integer'],
            [['Code'], 'string', 'max' => 36],
            [['UserID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['UserID' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'Code' => Yii::t('app', 'Code'),
            'Date' => Yii::t('app', 'Date'),
            'UserID' => Yii::t('app', 'UserID'),
            'Status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['ID' => 'UserID']);
    }
}
