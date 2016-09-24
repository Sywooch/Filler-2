<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bot".
 *
 * @property integer $PlayerID
 * @property integer $Level
 * @property integer $Secret
 *
 * @property User $player
 */
class Bot extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bot';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PlayerID'], 'required'],
            [['PlayerID', 'Level', 'Secret'], 'integer'],
            [['PlayerID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['PlayerID' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PlayerID' => Yii::t('app', 'PlayerID'),
            'Level' => Yii::t('app', 'Level'),
            'Secret' => Yii::t('app', 'Secret'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(User::className(), ['id' => 'PlayerID']);
    }
}
