<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "activity".
 *
 * @property integer $id
 * @property integer $motivation_id
 * @property integer $user_id
 * @property integer $money
 * @property integer $exp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Motivation $motivation
 * @property User $owner
 */
class Activity extends \yii\db\ActiveRecord
{
    public $currentLevel = 0; //текущий уровень пользователя в зависимости от настроек мотивации
    
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
        return 'activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['motivation_id', 'user_id'], 'required'],
            [['motivation_id', 'user_id', 'money', 'exp'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['motivation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Motivation::className(), 'targetAttribute' => ['motivation_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'motivation_id' => 'Motivation ID',
            'user_id' => 'Owner ID',
            'money' => 'Money',
            'exp' => 'Exp',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMotivation()
    {
        return $this->hasOne(Motivation::className(), ['id' => 'motivation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskcomplite()
    {
        return $this->hasMany(TaskComplite::className(), ['activity_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRewardcomplite()
    {
        return $this->hasMany(RewardComplite::className(), ['activity_id' => 'id']);
    }
    
    /**
     * @inheritdoc
     * @return ActivityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ActivityQuery(get_called_class());
    }
}
