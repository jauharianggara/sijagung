<?php

namespace backend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;

/**
 * ProvinsiController implements the CRUD actions for Provinsi model.
 */
class ProvinsiController extends ActiveController
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
  	    $searchModel = new \backend\models\ProvinsiSearch();
  			$provider =  $searchModel->search(\Yii::$app->request->queryParams);
  			$provider->setPagination(false);
  			return $provider;
  	}

    public $modelClass = 'backend\models\Provinsi';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
}
