<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lobby_bot".
 *
 * @property string $lobbyID
 * @property integer $level
 * @property integer $botsNumber
 *
 * @property Lobby $lobby
 */
class LobbyBot extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lobby_bot';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lobbyID'], 'required'],
            [['lobbyID', 'level', 'botsNumber'], 'integer'],
            [['lobbyID'], 'exist', 'skipOnError' => true, 'targetClass' => Lobby::className(), 'targetAttribute' => ['lobbyID' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lobbyID' => Yii::t('app', 'lobbyID'),
            'level' => Yii::t('app', 'level'),
            'botsNumber' => Yii::t('app', 'botsNumber'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLobby()
    {
        return $this->hasOne(Lobby::className(), ['ID' => 'lobbyID']);
    }
}
