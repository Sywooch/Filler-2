<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%game}}".
 *
 * @property string id
 * @property string $ColorMatrix
 * @property string $Comment
 * @property string $StartDate
 * @property string $FinishDate
 * @property string $LobbyID
 * @property integer $WinnerID
 *
 * @property Lobby $lobby
 * @property User $winner
 * @property GameDetail[] $gameDetails
 */
class Game extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%game}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ColorMatrix'], 'string'],
            [['StartDate', 'FinishDate'], 'safe'],
            [['LobbyID'], 'required'],
            [['LobbyID', 'WinnerID'], 'integer'],
            [['Comment'], 'string', 'max' => 100],
            [['LobbyID'], 'exist', 'skipOnError' => true, 'targetClass' => Lobby::className(), 'targetAttribute' => ['LobbyID' => 'id']],
            [['WinnerID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['WinnerID' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'ColorMatrix' => Yii::t('app', 'ColorMatrix'),
            'Comment' => Yii::t('app', 'Comment'),
            'StartDate' => Yii::t('app', 'StartDate'),
            'FinishDate' => Yii::t('app', 'FinishDate'),
            'LobbyID' => Yii::t('app', 'LobbyID'),
            'WinnerID' => Yii::t('app', 'WinnerID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLobby()
    {
        return $this->hasOne(Lobby::className(), ['id' => 'LobbyID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWinner()
    {
        return $this->hasOne(User::className(), ['id' => 'WinnerID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGameDetails()
    {
        return $this->hasMany(GameDetail::className(), ['GameID' => 'id']);
    }
}
