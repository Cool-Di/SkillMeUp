<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "reward".
 *
 * @property integer $id
 * @property integer $is_active
 * @property string $name
 * @property integer $motivation_id
 * @property integer $need_money
 * @property integer $min_exp
 * @property integer $min_level
 * @property integer $is_reusable
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class Reward extends \yii\db\ActiveRecord
{
    public $enough_points = true; //Хватает ли опыта и денег на награду
    public $box_color = ""; //цвет блока в списке задач
    public $cant_repeat = false; //запрет задачи на повторение(если она уже была)
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reward';
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
            [['motivation_id', 'created_by', 'updated_by', 'is_reusable'], 'integer'],
            ['is_active', 'default', 'value'=>1, 'isEmpty'=>true, 'on'=>'insert'],
            [['need_money', 'min_exp', 'min_level',], 'default', 'value'=>0],
            [['need_money', 'min_exp', 'min_level',], 'integer',  'min' => 0],
            [['name', 'motivation_id', 'created_by', 'updated_by'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
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
            'is_active' => 'Активность',
            'name' => 'Название',
            'motivation_id' => 'Motivation ID',
            'need_money' => 'Стоимость',
            //'need_exp' => 'Необходимый опыт (устарело)',
            'min_exp' => 'Минимальный опыт',
            'min_level' => 'Минимальный уровень',
            'is_reusable' => 'Многоразовая',
            'created_by' => 'Created By',
            'updated_by' => 'Edited By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
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
    public function getRewardcomplite()
    {
        return $this->hasMany(RewardComplite::className(), ['reward_id' => 'id']);
    }
    
    
    //Заводим переменную, определяющую достаточно ли у пользователя опыта для просмотра задач
    public function checkExp($useractivity)
    {
        $this->enough_points = $useractivity->exp >= $this->min_exp && $useractivity->money >= $this->need_money;     
        $this->box_color = $this->enough_points ? "aqua" : "grey";

        $this->cant_repeat = $this->rewardcomplite && !$this->is_reusable;
    }

    //Устанавливает значения по умолчанию для отображения в форме для новых елементов
    public function setDefaultValues() {
        $this->is_reusable = 1;
    }
}
