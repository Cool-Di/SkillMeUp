<?php

namespace frontend\controllers;

use Yii;
use common\models\Motivation;
use common\models\Activity;
use common\models\Task;
use common\models\TaskSearch;
use common\models\Notice;
use common\models\NoticeExt;

use frontend\controllers\MotivationBaseController;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends MotivationBaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['tasklist', 'index', 'create', 'update', 'views'],
                'rules' => [
                    [
                        'actions' => ['tasklist', 'index', 'create', 'update', 'views'],
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
     * Displays a list of tasks by motivation_id.
     * @param integer $id
     * @return mixed
     */
    /*public function actionList($id)
    {
        $motivation = $this->findModel($id);
        $tasks = $this->getMotivationTasks($id);
        return $this->render('list', [
                    'motivation' => $motivation,
                    'tasks' => $tasks,
                ]);
    }*/
    
    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        
        $motivation = $this->findModel($id);
        if(!Yii::$app->user->can('updateItem', ['item' => $motivation])) { //Проверка доступа к мотивации
            throw new ForbiddenHttpException('У Вас нет доступа к этой странице');
        }
        
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'motivation' => $motivation,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
//        return $this->render('views', [
//            'model' => $this->findTask($id),
//        ]);
        $model = $this->findTask($id);
        
        $motivation = $this->findModel($model->motivation_id);
        
        return $this->render('views', [
            'model' => $model,
            'motivation' => $motivation,
            'errors' => $model->errors
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'views' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $motivation = $this->findModel($id);
        if(!Yii::$app->user->can('updateItem', ['item' => $motivation])) { //Проверка доступа к мотивации
            throw new ForbiddenHttpException('У Вас нет доступа к этой странице');
        }


        $model = new Task();
        $model->setDefaultValues();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = Yii::$app->user->id;
            $model->motivation_id = $id;
            if($model->save()) {
                $text = "Добавлена задача \"".$model->name."\" в мотивационном проекте <a>".$motivation->name."</a>";
                NoticeExt::addNoticeByMotivation($motivation->id, $text);

                return $this->redirect(['task/index', 'id' => $motivation->id]);
            } else {
                //debugmessage($model->errors);
            }
        } 
        return $this->render('create', [
            'model' => $model,
            'motivation' => $motivation,
            'errors' => $model->errors
        ]);
        
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'views' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findTask($id);
        
        $motivation = $this->findModel($model->motivation_id);
        if(!Yii::$app->user->can('updateItem', ['item' => $motivation])) { //Проверка доступа к мотивации
            throw new ForbiddenHttpException('У Вас нет доступа к этой странице');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['views', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'motivation' => $motivation,
            ]);
        }
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findTask($id);
        
        $motivation = $this->findModel($model->motivation_id);
        if(!Yii::$app->user->can('updateItem', ['item' => $motivation])) { //Проверка доступа к мотивации
            throw new ForbiddenHttpException('У Вас нет доступа к этой странице');
        }
        
        $model->is_active = 0;
        $model->save();
        //$model->delete();
        return $this->redirect(['task/index', 'motivation_id' => $motivation->id]);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findTask($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
