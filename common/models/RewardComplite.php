<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "reward_complite".
 *
 * @property integer $id
 * @property integer $is_active
 * @property integer $activity_id
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $reward_id
 * @property integer $status
 * @property integer $money
 * @property integer $exp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $updatedBy
 * @property Activity $activity
 * @property User $createdBy
 * @property Reward $reward
 */
class RewardComplite extends \yii\db\ActiveRecord
{
    public $status_style; //виртуальное поле, зависящее от статуса
    public $status_icon; //виртуальное поле, зависящее от статуса
    public $status_help; //виртуальное поле, зависящее от статуса
    const STATUS_NEW = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_REJECTED = 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reward_complite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active', 'activity_id', 'created_by', 'updated_by', 'reward_id', 'status', 'money', 'exp'], 'integer'],
            [['activity_id', 'created_by', 'updated_by', 'reward_id', 'status', 'money', 'exp'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::className(), 'targetAttribute' => ['activity_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['reward_id'], 'exist', 'skipOnError' => true, 'targetClass' => Reward::className(), 'targetAttribute' => ['reward_id' => 'id']],
            
            [['status_style', 'status_icon', 'status_tooltip'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'is_active' => 'Is Active',
            'activity_id' => 'Activity ID',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'reward_id' => 'Reward ID',
            'status' => 'Status',
            'money' => 'Money',
            'exp' => 'Exp',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
     // this method is called after using TaskComplite::find()
    // you can set values for your virtual attributes here for example
    public function afterFind()
    {
        parent::afterFind();
        if($this->status == 0) {
            $this->status_style = "yellow";
            $this->status_icon = "fa-clock-o";
            $this->status_help = "Ожидает выполнения";
        } elseif($this->status == 1) {
            $this->status_style = "green";
            $this->status_icon = "fa-check-circle-o";
            $this->status_help = "Подтверждено";
        } elseif($this->status == 2) {
            $this->status_style = "red";
            $this->status_icon = "fa-close";
            $this->status_help = "Отказано";
        } else {
            $this->status_style = "black";
            $this->status_icon = "fa-question-circle";
            $this->status_help = "Ошибка";
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasOne(Activity::className(), ['id' => 'activity_id']);
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
    public function getReward()
    {
        return $this->hasOne(Reward::className(), ['id' => 'reward_id']);
    }
}
