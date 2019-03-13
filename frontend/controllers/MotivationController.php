<?php

namespace frontend\controllers;

use Yii;
use common\models\Motivation;
use common\models\MotivationSearch;
use common\models\MotivationExt;
use common\models\Activity;
use common\models\Task;
use common\models\TaskComplite;
use common\models\Reward;
use common\models\RewardComplite;

use frontend\controllers\MotivationBaseController;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * MotivationController implements the CRUD actions for Motivation model.
 */
class MotivationController extends MotivationBaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update', 'views'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'views'],
                        'allow' => true,
                        'roles' => ['@'], //@ - авторизованные пользователи
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    /**
     * Lists all Motivation models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'test' => 1,
        ]);
    }
    
    /**
     * Lists all Motivation models.
     * @return mixed
     */
    public function actionDashboard()
    {
        return $this->render('dashboard', []);
    }

    /**
     * Lists all Motivation models by author
     * @return mixed
     */
    public function actionMy()
    {
        $searchModel = new MotivationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->user->id);
        //debugmessage($dataProvider);
        return $this->render('my', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Motivation models by in progress
     * @return mixed
     */
    public function actionInprogress()
    {
        $searchModel = new MotivationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, false, ["inprogress_id" => Yii::$app->user->id]);
        //debugmessage($dataProvider);
        return $this->render('inprogress', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Motivation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(Yii::$app->request->post("start_activity") == 1) { //Если нажата кнопка "начать выполнение мотивации"
            if(MotivationExt::startActivity($id)) {
                Yii::$app->session->setFlash('activity_started', 'Вы присоединились к мотивационному проекту');
                return $this->refresh();
            } 
        }
        $model = $this->findModel($id);
        $userActivity = $this->findUserActivity($id); //участие текущего пользователя + список его выполненных задач
        //$activities = Activity::find()->with('user')->where(['motivation_id' => $id])->all(); //список тех, кто участвует в мотивации
        
        $activities = Activity::find()
                ->select(['ac.*', 'u.username', 'mo.name', 'ml.level'])
                ->from('activity ac')
                ->leftJoin('user u', 'ac.user_id = u.id')
                ->leftJoin('motivation mo', 'mo.id = ac.motivation_id')
                ->leftJoin('motivation_level ml', 'mo.id = ml.motivation_id AND ac.exp >= ml.exp')
                ->where(['ac.motivation_id' => $id])
                ->asArray()
                ->all(); //список тех, кто участвует в мотивации
        //debugmessage($activities);
        //$tasks = $this->getMotivationTasks($id, $model->activity);
        $tasks = Task::find()->leftJoin('task_complite', '`task`.`id` = `task_complite`.`task_id` AND `task`.`is_reusable` = 0 AND `task_complite`.`status` <> 2')->where(['motivation_id' => $id, 'task.is_active' => 1])->orderBy('name')->all();
        //$rewards = Reward::find()->where(['motivation_id' => $id, 'is_active' => 1])->orderBy('name')->all();
        $rewards = Reward::find()->leftJoin('reward_complite', '`reward`.`id` = `reward_complite`.`reward_id` AND `reward`.`is_reusable` = 0 AND `reward_complite`.`status` <> 2')->where(['motivation_id' => $id, 'reward.is_active' => 1])->orderBy('name')->all();
        //debugmessage($userActivity);
        return $this->render('views', [
            'model' => $model,
            'activities' => $activities,
            'useractivity' => $userActivity,
            'tasks' => $tasks,
            'rewards' => $rewards,
            'access' => [
                'can_control' => Yii::$app->user->can('updateItem', ['item' => $model]),
                'can_select_task' => $userActivity, //Может выбрать задачу на вполнение
                'can_select_reward' => $userActivity //Может выбрать награду
            ]
        ]);
    }
    
    /**
     * Displays a single Motivation model.
     * @param integer $id
     * @return mixed
     */
    public function actionControl($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('updateItem', ['item' => $model])) {
            throw new ForbiddenHttpException('У Вас нет доступа к этой странице');
        } else {
            //debugmessage($model->activity);
            //$tasks = $this->getMotivationTasks($id);
            $activities = Activity::find()->with('user')->where(['motivation_id' => $id])->all(); //список тех, кто участвует в мотивации
            $taskOnModeration = $this->findTaskComplete($id, TaskComplite::STATUS_NEW); //status = 0 на модерации
            $rewardOnModeration = $this->findRewardComplete($id, RewardComplite::STATUS_NEW); //status = 0 на модерации
            //debugmessage($tasks);
            return $this->render('control', [
                'model'             => $model,
                'activities'        => $activities,
                'taskOnModeration'  => $taskOnModeration,
                'rewardOnModeration'  => $rewardOnModeration,
                'access' => [
                    'can_control' => true,
                    'can_accept_task' => true,
                ]
            ]);
        }
    }
    
    public function actionAccepttask($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('updateItem', ['item' => $model])) {
            throw new ForbiddenHttpException('У Вас нет доступа к этой странице');
        } else { 
            if(preg_match("/^(1|2)$/", Yii::$app->request->post("sbm_type"))) {
                $taskComplite = TaskComplite::find()
                    ->where(['id' => Yii::$app->request->post("taskcomp_id")])
                    ->one();
                //debugmessage(Yii::$app->request->post("sbm_type"));
                $taskComplite->status = Yii::$app->request->post("sbm_type");
                $taskComplite->save(); //TODO сделать проверку надоступ к сохранению. Доступ имеют только те, кто назначен в админке проверяющим этой активности
                
                if(Yii::$app->request->post("sbm_type") == 1) { //Если задача принята, то прибавляем деньги и опыт
                    $activity = Activity::find()
                        ->where(['id' => $taskComplite['activity_id']])
                        ->one();
                    $activity->updateCounters(['money' => $taskComplite['money'], 'exp' => $taskComplite['exp']]);
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
    }
    
    //Модерация награды, принятие или отказ
    public function actionAcceptreward($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('updateItem', ['item' => $model])) {
            throw new ForbiddenHttpException('У Вас нет доступа к этой странице');
        } else { 
            if(preg_match("/^(1|2)$/", Yii::$app->request->post("sbm_type"))) {
                $rewardComplite = RewardComplite::find()
                    ->where(['id' => Yii::$app->request->post("rewardcomp_id")])
                    ->one();
                //debugmessage(Yii::$app->request->post("sbm_type"));
                //die();
                $rewardComplite->status = Yii::$app->request->post("sbm_type");
                $rewardComplite->save(); //TODO сделать проверку надоступ к сохранению. Доступ имеют только те, кто назначен в админке проверяющим этой активности
                
                if(Yii::$app->request->post("sbm_type") == 2 && $rewardComplite['money']) { //Если в награде отказано, то возвращаем деньги, которые отняли за награду
                    $activity = Activity::find()
                        ->where(['id' => $rewardComplite['activity_id']])
                        ->one();
                    $activity->updateCounters(['money' => $rewardComplite['money']]);
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
    }

    /**
     * Creates a new Motivation model.
     * If creation is successful, the browser will be redirected to the 'views' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Motivation();
        
        if ($model->load(Yii::$app->request->post())) {
            $model->owner_id = Yii::$app->user->id;
            if ($model->save()) {
                return $this->redirect(['views', 'id' => $model->id]);
            }
        } 
        
        return $this->render('create', [
            'model' => $model,
        ]);
        
    }

    /**
     * Updates an existing Motivation model.
     * If update is successful, the browser will be redirected to the 'views' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        //debugmessage(Yii::$app->user->can('updateMotivation'));
        $model = $this->findModel($id);
        if(Yii::$app->user->can('updateItem', ['item' => $model])) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['views', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
           //return $this->goHome();
           throw new ForbiddenHttpException('У Вас нет доступа к этой странице');
        }
    }

    /**
     * Deletes an existing Motivation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_active = false;
        $model->save();
        //$this->findModel($id)->delete();

        return $this->redirect(['my']);
    }
    
    /**
     * взять задачу
     */
    public function actionEditactivitytask($motivation_id, $task_id)
    {
        
        if(Yii::$app->request->post("start_task") == 1) { //Если нажата кнопка "выполнить эту задачу"
            if($this->startTask($motivation_id, $task_id, Yii::$app->request->post("activity_id"))) {
                Yii::$app->session->setFlash('task_started', 'Задача отправлена');
                return $this->redirect(['motivation/views', 'id' => $motivation_id]);
            }  else {
                echo "Ошибка добавления";
                debugmessage($this->errors);
            }
        }
    }
    
    /**
     * взять награду
     */
    public function actionEditactivityreward($motivation_id, $reward_id)
    {
        
        if(Yii::$app->request->post("get_reward") == 1) { //Если нажата кнопка "получить награду"
            if($this->startReward($motivation_id, $reward_id, Yii::$app->request->post("activity_id"))) {
                Yii::$app->session->setFlash('reward_started', 'Запрос на награду отправлен');
                return $this->redirect(['motivation/views', 'id' => $motivation_id]);
            } else {
                echo "Ошибка добавления";
                debugmessage($this->errors);
            }
        }
    }
}
