<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lobby_player".
 *
 * @property string $LobbyID
 * @property integer $PlayerID
 * @property string $Date
 * @property integer $Status
 *
 * @property Lobby $lobby
 * @property User $player
 */
class LobbyPlayer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lobby_player';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['LobbyID', 'PlayerID'], 'required'],
            [['LobbyID', 'PlayerID', 'Status'], 'integer'],
            [['Date'], 'safe'],
            [['LobbyID'], 'exist', 'skipOnError' => true, 'targetClass' => Lobby::className(), 'targetAttribute' => ['LobbyID' => 'id']],
            [['PlayerID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['PlayerID' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'LobbyID' => Yii::t('app', 'LobbyID'),
            'PlayerID' => Yii::t('app', 'PlayerID'),
            'Date' => Yii::t('app', 'Date'),
            'Status' => Yii::t('app', 'Status'),
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
    public function getPlayer()
    {
        return $this->hasOne(User::className(), ['id' => 'PlayerID']);
    }
}
