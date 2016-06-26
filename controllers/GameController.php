<?php

namespace app\controllers;

class GameController extends \yii\web\Controller
{
    public function actionGame()
    {
        return $this->render('game');
    }

}
