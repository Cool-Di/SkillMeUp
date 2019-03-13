<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property integer $is_active
 * @property string $name
 * @property integer $created_by
 * @property integer $motivation_id
 * @property integer $default_money
 * @property integer $default_exp
 * @property integer $min_exp
 * @property integer $is_reusable
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $createdBy
 * @property Motivation $motivation
 * @property TaskComplite[] $taskComplites
 */
class Task extends \yii\db\ActiveRecord
{
    public $enough_exp = true; //Хватает ли опыта на задачу
    public $box_color = ""; //цвет блока в списке задач
    public $cant_repeat = false; //запрет задачи на повторение(если она уже была)
    
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
        return 'task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active', 'created_by', 'motivation_id', 'is_reusable'], 'integer'],
            [['default_money', 'default_exp', 'min_exp'], 'integer', 'min' => 0],
            [['name', 'created_by', 'motivation_id', 'default_money', 'default_exp'], 'required'],
            ['min_exp', 'default','value'=>0],
            ['created_by', 'default', 'value'=>\Yii::$app->user->identity->id, 'isEmpty'=>true, 'on'=>'insert'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
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
            'is_active' => 'Is Active',
            'name' => 'Заголовок задачи',
            'created_by' => 'Кем создано',
            'motivation_id' => 'ID Мотивации',
            'default_money' => 'Награда деньги',
            'default_exp' => 'Награда опыт',
            'is_reusable' => 'Многоразовая',
            'created_at' => 'Время создания',
            'updated_at' => 'Время обновления',
            'min_exp' => 'Необходимый опыт',
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
    public function getMotivation()
    {
        return $this->hasOne(Motivation::className(), ['id' => 'motivation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskcomplite()
    {
        return $this->hasMany(TaskComplite::className(), ['task_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskQuery(get_called_class());
    }
    
    //Просто пример функции получения преобразованного поля, пока что не используется
    public function getBoxIcon()
    {
        if($this->enough_exp) {
            return 'fa-tasks';
        } else {
            return 'fa-close';
        }
    }
    
    //Заводим переменную, определяющую достаточно ли у пользователя опыта для просмотра задач
    public function checkExp($user_exp)
    {
        $this->enough_exp = $user_exp >= $this->min_exp;     
        $this->box_color = $this->enough_exp ? "aqua" : "grey";

        $this->cant_repeat = $this->taskcomplite && !$this->is_reusable;
    }

    //Устанавливает значения по умолчанию для отображения в форме для новых елементов
    public function setDefaultValues() {
        $this->is_reusable = 1;
    }
}
