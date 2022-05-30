<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "i_code".
 *
 * @property int $id
 * @property string|null $code
 * @property int|null $code_type 1自选2资金3冷底
 * @property string|null $code_name
 * @property float|null $open_per
 * @property float|null $up_down
 * @property string|null $created_time
 * @property string|null $updated_time
 */
class Code extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'i_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code_type'], 'integer'],
            [['open_per', 'up_down'], 'number'],
            [['created_time', 'updated_time'], 'safe'],
            [['code', 'code_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'code_type' => '1自选2资金3冷底',
            'code_name' => 'Code Name',
            'open_per' => 'Open Per',
            'up_down' => 'Up Down',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}
