<?php

namespace backend\models;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "berita".
 *
 * @property int $id
 * @property string $judul
 * @property string $isi
 * @property string $fileUpload
 * @property string $dateAdd
 * @property int $userAdd
 * @property string $dateEdit
 * @property int $userEdit
 */
class Berita extends \yii\db\ActiveRecord
{
	public $file_upload;
	public $image_upload;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'berita';
    }

		public function behaviors()
		{
		    return [
		      [
		        'class' => BlameableBehavior::className(),
		        'createdByAttribute' => 'userAdd',
		        'updatedByAttribute' => 'userEdit',
		      ],
		      [
		        'class' => TimestampBehavior::className(),
		        'createdAtAttribute' => 'dateAdd',
		        'updatedAtAttribute' => 'dateEdit',
		        'value' => new Expression('NOW()'),
		      ],
		    ];
		}


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['judul', 'isi', 'fileUpload'], 'required'],
            [['dateAdd', 'dateEdit', 'isi'], 'safe'],
            [['userAdd', 'userEdit'], 'integer'],
            [['judul', 'fileUpload'], 'string', 'max' => 255],
						[['file_upload'], 'file'],
						[['image_upload'], 'file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'judul' => 'Judul',
            'isi' => 'Isi',
            'fileUpload' => 'File Upload',
            'dateAdd' => 'Date Add',
            'userAdd' => 'User Add',
            'dateEdit' => 'Date Edit',
            'userEdit' => 'User Edit',
        ];
    }
}
