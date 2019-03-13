<?php

namespace common\models;

use Yii;
use yii\web\ServerErrorHttpException;

/**
 * MotivationExtention для манипуляций с базой
 */
class NoticeExt extends Notice
{
    /**
     * Метод добавления одного нотиса
     */
    public function addNotice($user_id, $activity_id = false, $text)
    {
        $modelNitice = new Notice();
        $modelNitice->user_id = $user_id;
        $modelNitice->created_by = Yii::$app->user->id;
        $modelNitice->text = $text;
        if($activity_id > 0)
            $modelNitice->activity_id = $activity_id;
        if($modelNitice->validate()) {
            $modelNitice->save();
        } else {
            debugmessage($modelNitice->errors);
        }
        //debugmessage("function call");
    }

    /**
     * Метод добавления нотисов для всех активити в проекте мотивации
     */
    public function addNoticeByMotivation($motivation_id, $text)
    {
        $activities = Activity::find()
            ->where(['motivation_id' => $motivation_id])
            ->all(); //список мотиваций, которые
        foreach($activities as $activity) {
            NoticeExt::addNotice($activity->user_id, $activity->id, $text);
        }
    }
}
