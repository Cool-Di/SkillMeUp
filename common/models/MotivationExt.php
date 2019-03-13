<?php

namespace common\models;

use Yii;
use yii\web\ServerErrorHttpException;

/**
 * MotivationExtention для манипуляций с базой
 */
class MotivationExt extends Motivation
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
            if($item->validate()) {
                return $item->save();
            } else {
               throw new ServerErrorHttpException('Ошибка запуска мотивации');
            }
        } else {
            //действия, если ативность мотивации уже запущена
        }
        return false;
    }
}
