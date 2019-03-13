<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "motivation_level".
 *
 * @property integer $id
 * @property integer $is_active
 * @property integer $motivation_id
 * @property integer $exp
 * @property integer $level
 * @property integer $created_by
 * @property integer $edited_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $createdBy
 * @property Motivation $motivation
 */
class MotivationLevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'motivation_level';
    }
    
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
    public function rules()
    {
        return [
            [['motivation_id', 'created_by', 'exp', 'level'], 'required'],
            ['is_active', 'default', 'value'=>1, 'isEmpty'=>true, 'on'=>'insert'],
            [['exp', 'level'], 'integer',  'min' => 0],
            [['motivation_id', 'created_by', 'edited_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['edited_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['edited_by' => 'id']],
            [['motivation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Motivation::className(), 'targetAttribute' => ['motivation_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'motivation_id' => 'Мотивация',
            'exp' => 'Опыт',
            'level' => 'Уровень',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEditedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'edited_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMotivation()
    {
        return $this->hasOne(Motivation::className(), ['id' => 'motivation_id']);
    }
}
