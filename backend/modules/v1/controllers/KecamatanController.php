<?php

namespace backend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;

/**
 * KecamatanController implements the CRUD actions for Kecamatan model.
 */
class KecamatanController extends ActiveController
{

    public function behaviors()
    {
      return [
  		[
  			'class' => \yii\filters\ContentNegotiator::className(),
  			'formats' => [
  				'application/json' => \yii\web\Response::FORMAT_JSON,
  			],
  		],
  		[
        'class' => \yii\filters\auth\CompositeAuth::className(),
          'authMethods' => [
              \yii\filters\auth\HttpBasicAuth::className(),
              \yii\filters\auth\HttpBearerAuth::className(),
              \yii\filters\auth\QueryParamAuth::className(),
          ],
        ],
      ];
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
  	{
  	    $searchModel = new \backend\models\KecamatanSearch();
  			$provider =  $searchModel->search(\Yii::$app->request->queryParams);
  			$provider->setPagination(false);
  			return $provider;
  	}

    public $modelClass = 'backend\models\Kecamatan';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
}
