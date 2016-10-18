<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lobby".
 *
 * @property string $id
 * @property string $Name
 * @property integer $SizeX
 * @property integer $SizeY
 * @property integer $ColorsNumber
 * @property integer $PlayersNumber
 * @property string $Date
 * @property integer $CreatorID
 * @property integer $Status
 *
 * @property Game[] $games
 * @property User $creator
 * @property LobbyPlayer[] $lobbyPlayers
 * @property User[] $players
 */
class Lobby extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lobby';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SizeX', 'SizeY', 'ColorsNumber', 'PlayersNumber', 'CreatorID', 'Status'], 'integer'],
            [['ColorsNumber', 'PlayersNumber', 'CreatorID'], 'required'],
            [['Date'], 'safe'],
            [['Name'], 'string', 'max' => 100],
            [['CreatorID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['CreatorID' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'Name' => Yii::t('app', 'Name'),
            'SizeX' => Yii::t('app', 'SizeX'),
            'SizeY' => Yii::t('app', 'SizeY'),
            'ColorsNumber' => Yii::t('app', 'ColorsNumber'),
            'PlayersNumber' => Yii::t('app', 'PlayersNumber'),
            'Date' => Yii::t('app', 'Date'),
            'CreatorID' => Yii::t('app', 'CreatorID'),
            'Status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGames()
    {
        return $this->hasMany(Game::className(), ['LobbyID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'CreatorID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLobbyPlayers()
    {
        return $this->hasMany(LobbyPlayer::className(), ['LobbyID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayers()
    {
        return $this->hasMany(User::className(), ['id' => 'PlayerID'])->viaTable('lobby_player', ['LobbyID' => 'id']);
    }
}
