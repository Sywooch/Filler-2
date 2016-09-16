<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lobby_player".
 *
 * @property string $LobbyID
 * @property integer $PlayerID
 * @property string $Date
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
            [['LobbyID', 'PlayerID'], 'integer'],
            [['Date'], 'safe'],
            [['LobbyID'], 'exist', 'skipOnError' => true, 'targetClass' => Lobby::className(), 'targetAttribute' => ['LobbyID' => 'ID']],
            [['PlayerID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['PlayerID' => 'ID']],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLobby()
    {
        return $this->hasOne(Lobby::className(), ['ID' => 'LobbyID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(User::className(), ['id' => 'PlayerID']);
    }

    // public function getLobbygame()
    // {
    //     return $this -> hasOne(Lobby::className(), ['ID' => 'LobbyID'])
    //                  -> viaTable('user', ['id' => 'PlayerID']);
    //                  // -> viaTable('game', ['LobbyID' => 'ID']);
    // }
}
