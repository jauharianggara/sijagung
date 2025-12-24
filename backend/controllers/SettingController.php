<?php

namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Setting;

class SettingController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = Setting::find()->one();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->render('index', [
                'model' => $model,
            ]);
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }

}
