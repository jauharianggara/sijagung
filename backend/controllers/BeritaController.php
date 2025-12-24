<?php

namespace backend\controllers;

use Yii;
use backend\models\Berita;
use backend\models\BeritaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\db\Expression;

/**
 * BeritaController implements the CRUD actions for Berita model.
 */
class BeritaController extends Controller
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
     * Lists all Berita models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BeritaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionList()
    {
        $searchModel = new BeritaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Berita model.
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
     * Creates a new Berita model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     public function actionCreate()
    {
        $model = new Berita();

       	if ($model->load(Yii::$app->request->post())) {
  			  $model->file_upload = UploadedFile::getInstance($model,'file_upload');
  			  if($model->file_upload){
  				  $path = Yii::getAlias('@app') .'/web/file/';
  				  $link = '/file/';
  				  if(!is_dir($path)):
  					  mkdir($path, 0777, true);
  				  endif;

  				  if(is_dir($path)):
  					  $name = 'file_'.rand(1000,9999) . time();
  					  $model->file_upload->saveAs($path . $name . '.' . $model->file_upload->extension);
  					  $model->fileUpload = $link.$name . '.' . $model->file_upload->extension;
  				  endif;
  			  }

          $model->image_upload = UploadedFile::getInstance($model,'image_upload');
          if($model->image_upload){
  				  $path = Yii::getAlias('@app') .'/web/foto/';
  				  $link = '/foto/';
  				  if(!is_dir($path)):
  					  mkdir($path, 0777, true);
  				  endif;

  				  if(is_dir($path)):
  					  $name = 'image_'.rand(1000,9999) . time();
  					  $model->image_upload->saveAs($path . $name . '.' . $model->file_upload->extension);
  					  $model->imageUpload = $link.$name . '.' . $model->file_upload->extension;
  				  endif;
  			  }


			    if($model->save()){
            return $this->redirect(['view', 'id' => $model->id]);
          }else{
            return $this->render('create', [
                'model' => $model,
            ]);
          }

        }else{
          return $this->render('create', [
              'model' => $model,
          ]);
        }


    }

    /**
     * Updates an existing Berita model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
        public function actionUpdate($id)
     {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
			      $model->file_upload = UploadedFile::getInstance($model,'file_upload');
    			  if($model->file_upload){
    				  $path = Yii::getAlias('@app') .'/web/file/';
    				  $link = '/file/';
    				  if(!is_dir($path)):
    					  mkdir($path, 0777, true);
    				  endif;

    				  if(is_dir($path)):
    					  $name = 'file_'.rand(1000,9999) . time();
    					  $model->file_upload->saveAs($path . $name . '.' . $model->file_upload->extension);
    					  $model->fileUpload = $link.$name . '.' . $model->file_upload->extension;
      				  endif;
      			}

            $model->image_upload = UploadedFile::getInstance($model,'image_upload');
    			  if($model->image_upload){
    				  $path = Yii::getAlias('@app') .'/web/foto/';
    				  $link = '/foto/';
    				  if(!is_dir($path)):
    					  mkdir($path, 0777, true);
    				  endif;

    				  if(is_dir($path)):
    					  $name = 'image_'.rand(1000,9999) . time();
    					  $model->image_upload->saveAs($path . $name . '.' . $model->file_upload->extension);
    					  $model->imageUpload = $link.$name . '.' . $model->file_upload->extension;
      				  endif;
      			}


    			    if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
              }else{
            return $this->render('update', [
                'model' => $model,
            ]);
          }

		}else{
            return $this->render('update', [
                'model' => $model,
            ]);
          }


	}

    /**
     * Deletes an existing Berita model.
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
     * Finds the Berita model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Berita the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Berita::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
