<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%game_detail}}".
 *
 * @property string $ID
 * @property integer $ColorIndex
 * @property integer $Points
 * @property string $Date
 * @property integer $PlayerID
 * @property string $GameID
 *
 * @property Game $game
 * @property User $player
 */
class GameDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%game_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ColorIndex', 'Points', 'PlayerID', 'GameID'], 'required'],
            [['ColorIndex', 'Points', 'PlayerID', 'GameID'], 'integer'],
            [['Date'], 'safe'],
            [['GameID'], 'exist', 'skipOnError' => true, 'targetClass' => Game::className(), 'targetAttribute' => ['GameID' => 'ID']],
            [['PlayerID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['PlayerID' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'ColorIndex' => Yii::t('app', 'ColorIndex'),
            'Points' => Yii::t('app', 'Points'),
            'Date' => Yii::t('app', 'Date'),
            'PlayerID' => Yii::t('app', 'PlayerID'),
            'GameID' => Yii::t('app', 'GameID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGame()
    {
        return $this->hasOne(Game::className(), ['ID' => 'GameID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(User::className(), ['ID' => 'PlayerID']);
    }
}
