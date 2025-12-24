<?php

namespace backend\controllers;

use Yii;
use backend\models\NotifSearch;
use backend\models\NotifUser;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NomorPendaftaranPakanController implements the CRUD actions for NomorPendaftaranPakan model.
 */
class NotifController extends Controller
{
    public function actionIndex()
    {
        $notifUser = NotifUser::updateAll(['status' => 1], 'id_user = '.\Yii::$app->user->id);
        $searchModel = new NotifSearch();
        $searchModel->id_user = \Yii::$app->user->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort([
            'attributes'=>[
              'tanggal'=>[
                'asc' => ['notif.tanggal' => SORT_ASC],
                'desc' => ['notif.tanggal' => SORT_DESC],
              ],
            ],
            'defaultOrder' => [
              'tanggal' => SORT_DESC,
            ]
        ]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
