<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "motivation".
 *
 * @property integer $id
 * @property string $name
 * @property integer $owner_id
 *
 * @property User $owner
 */
class Motivation extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        //даты создания и редактирования настраиваются здесь
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'motivation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'owner_id'], 'required'],
            [['owner_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner_id' => 'id']],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'owner_id' => 'Автор',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasMany(Activity::className(), ['motivation_id' => 'id']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getMotivationLevel()
    {
        return $this->hasMany(MotivationLevel::className(), ['motivation_id' => 'id']);
    }
    
}
