<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $ID
 * @property string $Name
 * @property string $Password
 * @property string $Email
 * @property string $RegistrationDate
 * @property integer $Enable
 * @property string $ActivityMarker
 * @property string $GameMarker
 * @property integer $Rating
 *
 * @property Game[] $games
 * @property GameDetail[] $gameDetails
 * @property Lobby[] $lobbies
 * @property LobbyPlayer[] $lobbyPlayers
 * @property Lobby[] $lobbies0
 * @property Recovery[] $recoveries
 */
class User extends \yii\db\ActiveRecord
{
	const REGISTRATION = 'registration';
	const REGISTRATION_AJAX = 'registration-ajax';
	const UPDATE = 'update';
	const UPDATE_PASSWORD = 'update-password';
	const FORGOT = 'forgot';
	const RECOVERY = 'recovery';



	/**
	 *  Контрольный пароль.
	 *
	 */
	public $ControlPassword;

	/**
	 *  Контрольный код.
	 *
	 */
	public $ControlCode;

	public $Captcha;

	

	// public function scenarios()
	// {
	//     return [
	//         self::REGISTRATION => ['username', 'password'],
	//         self::REGISTRATION_AJAX => ['username', 'email', 'password'],
	//         // array('Name', 'required', 'on' => 'registration, registration-ajax, update, update-password'),
	//     ];
	// }

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'user';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['Name', 'Password', 'Email'], 'required'],
			[['RegistrationDate', 'ActivityMarker', 'GameMarker'], 'safe'],
			[['Enable', 'Rating'], 'integer'],
			[['Name', 'Email'], 'string', 'max' => 50],
			[['Password'], 'string', 'max' => 60],
			[['ControlPassword'], 'compare', 'compareAttribute' => 'Password', 'on' => 'registration, registration-ajax, update-password, recovery'],
			[['ControlCode'], 'captcha', 'on' => 'registration'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			// 'ID' => Yii::t('app', 'ID'),
			// 'Name' => Yii::t('app', 'User Name'),
			// 'Password' => Yii::t('app', 'User Password'),
			// 'Email' => Yii::t('app', 'E-mail'),
			// 'RegistrationDate' => Yii::t('app', 'RegistrationDate'),
			// 'Enable' => Yii::t('app', 'Enable'),
			// 'ActivityMarker' => Yii::t('app', 'ActivityMarker'),
			// 'GameMarker' => Yii::t('app', 'GameMarker'),
			// 'Rating' => Yii::t('app', 'Rating'),

			'ID' => Yii::t('Dictionary', 'ID'),
			'Name' => Yii::t('Dictionary', 'Player name'),
			'Password' => Yii::t('Dictionary', 'Password'),
			'ControlPassword' => Yii::t('Dictionary', 'Confirm password'),
			'Email' => Yii::t('Dictionary', 'E-mail'),
			'RegistrationDate' => Yii::t('Dictionary', 'RegistrationDate'),
			'Enable' => Yii::t('Dictionary', 'Enable'),
			'ActivityMarker' => Yii::t('Dictionary', 'ActivityMarker'),
			'GameMarker' => Yii::t('Dictionary', 'GameMarker'),
			'Rating' => Yii::t('Dictionary', 'Rating'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getGames()
	{
		return $this->hasMany(Game::className(), ['WinnerID' => 'ID']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getGameDetails()
	{
		return $this->hasMany(GameDetail::className(), ['PlayerID' => 'ID']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLobbies()
	{
		return $this->hasMany(Lobby::className(), ['CreatorID' => 'ID']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLobbyPlayers()
	{
		return $this->hasMany(LobbyPlayer::className(), ['PlayerID' => 'ID']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLobbies0()
	{
		return $this->hasMany(Lobby::className(), ['ID' => 'LobbyID'])->viaTable('lobby_player', ['PlayerID' => 'ID']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRecoveries()
	{
		return $this->hasMany(Recovery::className(), ['UserID' => 'ID']);
	}
}