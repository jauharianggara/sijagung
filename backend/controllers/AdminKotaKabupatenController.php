<?php

namespace backend\controllers;

use Yii;
use backend\models\User;
use hscstudio\mimin\models\AuthAssignment;

use backend\models\AdminKotaKabupaten;
use backend\models\AdminKotaKabupatenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminKotaKabupatenController implements the CRUD actions for AdminKotaKabupaten model.
 */
class AdminKotaKabupatenController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AdminKotaKabupaten models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminKotaKabupatenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AdminKotaKabupaten model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AdminKotaKabupaten model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminKotaKabupaten();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
          $user = User::find()->where(['email'=>$model->email])->one();
          if($user == null){
              $user  = new User();
              $user->username = $model->username;
              $user->nama = $model->nama;
              $user->setPassword('1234567');
              $user->email = $model->email;
              $user->status = 10;

              if($user->save()){
                $model->id_user =$user->id;
                $model->save();
                $authAssignment = new AuthAssignment();
                $authAssignment->item_name='adminkotakabupaten';
                $authAssignment->user_id=$user->id;
                $date = date_create();
                $authAssignment->created_at=date_timestamp_get($date);
                if(!$authAssignment->save()){
              }
            }
          }
          return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AdminKotaKabupaten model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AdminKotaKabupaten model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AdminKotaKabupaten model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminKotaKabupaten the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminKotaKabupaten::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
