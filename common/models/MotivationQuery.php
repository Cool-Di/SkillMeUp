<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Motivation;
use common\models\Activity;
use yii\web\ServerErrorHttpException;

/**
 * MotivationExtention для манипуляций с базой
 */
class MotivationQuery extends Motivation
{
    /**
     * Метод для запуска ативности в мотивации
     */
    public function startActivity($motivation_id, $user_id = null)
    {
        if(!$user_id){
            $user_id = Yii::$app->user->id;
        }
        $oldActivity = Activity::find()->where(['user_id' => $user_id, 'motivation_id' => $motivation_id])->one();
        if(!$oldActivity) {
            $item = new Activity();
            $item->is_active = true;
            $item->motivation_id = $motivation_id;
            $item->user_id = $user_id;
            if($item->save()) {
                return $item->save();
            } else {
               throw new ServerErrorHttpException('Ошибка запуска мотивации');
            }
        } else {
            //действия, если ативность мотивации уже запущена
        }
        return false;
    }
    
    /**
     * Метод для запуска задачи в активити
     */
    public function startTask($motivation_id, $task_id, $activity_task_id = null, $user_id = null)
    {
        if(!$user_id){
            $user_id = Yii::$app->user->id;
        }
        
        $item = new Activity();
        $item->is_active = true;
        $item->motivation_id = $motivation_id;
        $item->user_id = $user_id;
        if($item->save()) {
            return $item->save();
        } else {
           throw new ServerErrorHttpException('Ошибка запуска мотивации');
        }
        
        return true;
    }
}
