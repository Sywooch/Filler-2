<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;



/**
 * This is the model class for table "user".
 *
 * @property integer $id
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

	

	/**
	 *	Хеширование пароля перед сохранением новой модели.
	 *
	 */
	public function beforeSave($insert) {
		if (parent::beforeSave($insert)) {
			// Если модель новая или обновление пароля или восстановление пароля:
			if ($this -> isNewRecord || $this -> getScenario() == 'update-password' || $this -> getScenario() == 'recovery')
				// Пароль хешируется.
				$this -> Password = Yii::$app -> getSecurity() -> generatePasswordHash($this -> Password);
			return true;
		}
		else
			return false;
	}

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
			['Name', 'required', 'on' => ['registration', 'registration-ajax', 'update', 'update-password']],
			['Email', 'required', 'on' => ['registration', 'registration-ajax', 'update', 'update-password', self::FORGOT]],
			[['Password', 'ControlPassword'], 'required', 'on' => ['registration', 'registration-ajax', 'update-password', 'recovery']],
			['imageFile', 'required', 'on' => ['update', 'update-password']],

			[['Enable', 'Rating'], 'number', 'integerOnly' => true],
			['Name', 'string', 'min' => 2, 'max' => 20, 'tooShort' => Yii::t('Dictionary', 'Field «{attribute}» must contain at least {min} characters')],
			[['Password', 'Email'], 'string', 'min' => 5, 'max' => 60, 'tooShort' => Yii::t('Dictionary', 'Field «{attribute}» must contain at least {min} characters')],
			['ControlCode', 'string', 'min' => 1, 'max' => 10],
			[['Email'], 'email'],

			[['Email'], 'unique', 'on' => ['registration', 'update', 'update-password']],
			[['ControlPassword'], 'compare', 'compareAttribute' => 'Password', 'on' => ['registration', 'registration-ajax', 'update-password', 'recovery']],
			['ControlCode', 'captcha', 'on' => 'registration'],

			[['ActivityMarker', 'imageFile'], 'safe'],
			[['id', 'Name', 'Password', 'Email', 'imageFile', 'RegistrationDate', 'Enable', 'GameMarker', 'Rating'], 'safe', 'on' => 'search'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('Dictionary', 'id'),
			'Name' => Yii::t('Dictionary', 'Player name'),
			'Password' => Yii::t('Dictionary', 'Password'),
			'ControlPassword' => Yii::t('Dictionary', 'Confirm password'),
			'Email' => Yii::t('Dictionary', 'E-mail'),
			'imageFile' => Yii::t('Dictionary', 'Image file'),
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
		return $this->hasMany(Game::className(), ['WinnerID' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getGameDetails()
	{
		return $this->hasMany(GameDetail::className(), ['PlayerID' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLobbies()
	{
		return $this->hasMany(Lobby::className(), ['CreatorID' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLobbyPlayers()
	{
		return $this->hasMany(LobbyPlayer::className(), ['PlayerID' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLobbies0()
	{
		return $this->hasMany(Lobby::className(), ['id' => 'LobbyID'])->viaTable('lobby_player', ['PlayerID' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRecoveries()
	{
		return $this->hasMany(Recovery::className(), ['UserID' => 'id']);
	}

	/**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(Bot::className(), ['PlayerID' => 'id']);
    }
}
