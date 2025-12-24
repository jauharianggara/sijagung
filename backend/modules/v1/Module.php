<?php

namespace backend\modules\v1;

/**
 * v1 module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\v1\controllers';

	
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        // disable the user session
        \Yii::$app->user->enableSession = false;
        \Yii::$app->user->enableAutoLogin = false;
    }
}
