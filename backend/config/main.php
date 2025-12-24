<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'SIJAGUNG',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'backend\modules\v1\Module',
        ],
        'mimin' => [
            'class' => '\hscstudio\mimin\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'dd MMMM yyyy',
            'datetimeFormat' => 'dd MMMM yyyy HH:mm:ss',
            'decimalSeparator' => ',',
            'thousandSeparator' => '.',
            'currencyCode' => 'IDR',
            'locale' => 'id_ID',
            'defaultTimeZone' => 'Asia/Jakarta',
            'timeZone'=> 'Asia/Jakarta',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => false],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'smpjejaka-synergy-00218379',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'collectorapi/auth',
                    'extraPatterns' => [
                        'POST login' => 'login',
                    ]
                ],
            ],
        ],

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            // only support DbManager
        ],
    ],
    'as access' => [
        'class' => '\hscstudio\mimin\components\AccessControl',
        'allowActions' => [
            // add wildcard allowed action here!
            'site/*',
            'notif/index',
            'berita/view',
            'provinsi/list',
            'kota-kabupaten/list',
            'kecamatan/list',
            'kelurahan/list',
            'sub-unsur/list',
            'hasil-kerja/list',
            'data-proyeksi/total-proyeksi',

            //'debug/*',
            'mimin/*',
            // only in dev mode
            'v1/*',
        ],
    ],
    'params' => $params,
];