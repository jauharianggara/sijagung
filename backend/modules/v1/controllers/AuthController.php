<?php

namespace backend\modules\v1\controllers;

use Yii;
use yii\filters\VerbFilter;
use common\models\User;
use yii\web\NotFoundHttpException;
use yii\base\Model;
use yii\rest\ActiveController;
/**
 * AuthController implements the CRUD actions for User model.
 */
class AuthController extends ActiveController
{
    public function behaviors(){
        return [
            [
                'class' => \yii\filters\ContentNegotiator::className(),
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    public function actionLogin(){
        $post = Yii::$app->request->post();
        $username = $post['username'];
        $password = $post['password'];
        $u = new \Yii::$app->user->identityClass;
        $user = ($u instanceof User ? $u->findByUsername($username) : User::findByUsername($username));
        if ($user && $user->validatePassword($password)) {
          return [
                  'status'=>'200',
                  'token'=>$user->auth_key,
          ];
        } else {
            throw new \yii\web\UnauthorizedHttpException;
        }
    }
    public $modelClass = 'common\models\User';
}
