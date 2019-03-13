<?php

namespace frontend\controllers;

use Yii;
use common\models\Motivation;
use common\models\MotivationSearch;
use common\models\MotivationExt;
use common\models\MotivationLevel;
use common\models\Activity;
use common\models\Task;
use common\models\TaskComplite;
use common\models\Reward;
use common\models\RewardComplite;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\web\ServerErrorHttpException;

/**
 * MotivationBaseController общий класс для контролеров управления мотивациями.
 */
class MotivationBaseController extends Controller
{
    public $errors = false; //собираем ошибки

    /**
     * Finds the Motivation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Motivation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Motivation::find()->with(['owner'])->where(['id' => $id, 'is_active' => true])->one();
        /*$model = Motivation::find()->with([
            'owner',
            'activity' => function ($query) {
                $query->andWhere(['user_id' => Yii::$app->user->id]);
            },
        ])->where(['id' => $id, 'is_active' => true])->one();*/
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function findUserActivity($motivation_id, $user_id = false)
    {
        if(Yii::$app->user->isGuest) { //для неавторизованных пользователей нельзя выбрать активность
            return false;
        }
        if(!$user_id) {
            $user_id = Yii::$app->user->id;
        }
        $userActivity = Activity::find()->joinWith([
            'taskcomplite tk' => function ($query) {
                $query->joinWith(['task t'])->limit(3)->orderBy('id DESC');
            }])
            ->joinWith([
            'rewardcomplite rk' => function ($query) {
                $query->joinWith(['reward r'])->limit(3)->orderBy('id DESC');
            }])
            ->where(['activity.motivation_id' => $motivation_id, 'activity.user_id' => Yii::$app->user->id])->one();
        if($userActivity) { //TODO сделать получение уровня одним запросом с получением активности
            //Получаем текущий уровень пользователя
            $currentLevel = MotivationLevel::find()
                ->where('exp <= :currentExp', [':currentExp' => $userActivity->exp])->orderBy('level DESC')
                ->limit(1)
                ->one();
            $userActivity->currentLevel = $currentLevel ? $currentLevel->level : 0;
        }
        //debugmessage($currentLevel->level);
        
        return $userActivity;
    }
    
    //Выбор выполненных задач в мотивации
    protected function findTaskComplete($motivation_id, $status = false)
    {
        $taskComplite = TaskComplite::find()->joinWith(['task', 'activity.user'])
            ->where(['activity.motivation_id' => $motivation_id]);
        if($status !== false) {
            $taskComplite -> andWhere(['task_complite.status' => $status]);
        }
        $taskComplite->limit(10)->orderBy("id ASC");
        //debugmessage($taskComplite);
        return $taskComplite->all();
    }
    
    //Выбор взятых наград
    protected function findRewardComplete($motivation_id, $status = false)
    {
        $rewardComplite = RewardComplite::find()->joinWith(['reward', 'activity.user'])
            ->where(['activity.motivation_id' => $motivation_id]);
        if($status !== false) {
            $rewardComplite -> andWhere(['reward_complite.status' => $status]);
        }
        $rewardComplite->limit(10)->orderBy("id ASC");
        //debugmessage($taskComplite);
        return $rewardComplite->all();
    }
    
    protected function getMotivationTasks($id)
    {
        //debugmessage($activity);
        $tasks = Task::find()->where(['motivation_id' => $id]);
        $tasks->orderBy('name');
        
        return $tasks->all();
    }
    
    protected function getOneTask($task_id)
    {
        //debugmessage($activity);
        $tasks = Task::find()->where(['id' => $task_id]);
        return $tasks->one();
    }
    
    protected function getOneReward($item_id)
    {
        //debugmessage($activity);
        $rewards = Reward::find()->where(['id' => $item_id]);
        return $rewards->one();
    }
    
    /**
     * получаем все последние выполненые мотивации пользователя
     */
//    protected function getTasksComplite($activity_id)
//    {
//        //debugmessage($activity_id);
//        $tasks = TaskComplite::find()->where(['activity_id' => $activity_id]);
//        $tasks->orderBy('id DESC');
//        
//        return $tasks->all();
//    }
    
    /**
     * Метод для запуска задачи в активити
     */
    public function startTask($motivation_id, $task_id, $activity_id)
    {
        $created_by = Yii::$app->user->id;
        
        $task = $this->getOneTask($task_id);

        //Если задача одноразовая, то проверяем не была ли она уже взята
        if(!$task->is_reusable) {
            $taskComplite = TaskComplite::find()->where(['task_id' => $task->id, 'activity_id' => $activity_id])->andWhere(['<>','status', 2])->one();
            $reject_duplicate = $taskComplite ? true : false; //Такая задача уже была взята, нельзя взять вторую
            $this->errors[] = "Попытка взять задачу, которая уже была принята";
        }

        $item = new TaskComplite();
        $item->is_active = true;
        $item->task_id = $task_id;
        $item->activity_id = $activity_id;
        $item->created_by = $created_by;
        $item->status = 0; //статус - не подтверждено
        $item->money = $task->default_money;
        $item->exp = $task->default_exp;
        if($item->validate() && !$reject_duplicate) {
            return $item->save();
        } else {
            $this->errors += $item->errors;
           return false;
           //throw new ServerErrorHttpException('Ошибка выполнения задачи');
        }
    }
    
    /**
     * Метод для получения награды в активити
     */
    public function startReward($motivation_id, $reward_id, $activity_id)
    {
        $created_by = Yii::$app->user->id;
        
        $reward = $this->getOneReward($reward_id);

        //Если награда одноразовая, то проверяем не была ли она уже взята
        if(!$reward->is_reusable) {
            $rewardComplite = RewardComplite::find()->where(['reward_id' => $reward->id, 'activity_id' => $activity_id])->andWhere(['<>','status', 2])->one();
            $reject_duplicate = $rewardComplite ? true : false; //Такая награда уже была взята, нельзя взять вторую
            $this->errors[] = "Попытка взять награду, которая уже была принята";
        }
        
        $item = new RewardComplite();
        $item->is_active = true;
        $item->reward_id = $reward_id;
        $item->activity_id = $activity_id;
        $item->created_by = $created_by;
        $item->updated_by = $created_by;
        $item->status = 0; //статус - не подтверждено
        $item->money = $reward->need_money;
        $item->exp = $reward->min_exp;
        if($item->validate() && !$reject_duplicate) {
            $done = $item->save();
            //Сразу блокируем деньги, чтобы нельзя было на них что-то купить
            $activity = Activity::find()
                    ->where(['id' => $activity_id])
                    ->one();
            $activity->updateCounters(['money' => (-1)*$reward->need_money]);
            return $done;
        } else {
            $this->errors += $item->errors;
            return false;
           //throw new ServerErrorHttpException('Ошибка выполнения задачи');
        }
    }
}
