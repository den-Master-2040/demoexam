<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requisitions".
 *
 * @property int $id
 * @property string $fio_user
 * @property string $gos_num
 * @property string $description
 * @property int $status
 */
class Requisitions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'requisitions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio_user', 'gos_num', 'description', 'status'], 'required'],
            [['status'], 'integer'],
            [['fio_user', 'gos_num'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio_user' => 'Fio User',
            'gos_num' => 'Gos Num',
            'description' => 'Description',
            'status' => 'Status',
        ];
    }
}
