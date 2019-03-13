<?php

namespace frontend\controllers;

use Yii;
use common\models\Reward;
use common\models\RewardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

/**
 * RewardController implements the CRUD actions for Reward model.
 */
class RewardController extends MotivationBaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Reward models.
     * @return mixed
     */
    public function actionIndex($motivation_id)
    {
        $motivation = $this->findModel($motivation_id);
        if(!Yii::$app->user->can('updateItem', ['item' => $motivation])) { //Проверка доступа к мотивации
            throw new ForbiddenHttpException('У Вас нет доступа к этой странице');
        } 
        
        $searchModel = new RewardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $motivation_id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'motivation' => $motivation,
        ]);
    }

    /**
     * Displays a single Reward model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($motivation_id)
    {
        return $this->render('views', [
            'model' => $this->findRewardModel($motivation_id),
        ]);
    }

    /**
     * Creates a new Reward model.
     * If creation is successful, the browser will be redirected to the 'views' page.
     * @return mixed
     */
    public function actionCreate($motivation_id)
    {
        $motivation = $this->findModel($motivation_id);

        
        if(!Yii::$app->user->can('updateItem', ['item' => $motivation])) { //Проверка доступа к мотивации
            throw new ForbiddenHttpException('У Вас нет доступа к этой странице');
        }
        
        $model = new Reward();
        $model->setDefaultValues();
        //$model->is_reusable = 1;

        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = Yii::$app->user->id;
            $model->updated_by = Yii::$app->user->id;
            $model->motivation_id = $motivation_id;
            if($model->save()) {
                return $this->redirect(['reward/index', 'motivation_id' => $motivation->id]);
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
     * Updates an existing Reward model.
     * If update is successful, the browser will be redirected to the 'views' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findRewardModel($id);
        
        $motivation = $this->findModel($model->motivation_id);
        
        if(!Yii::$app->user->can('updateItem', ['item' => $motivation])) { //Проверка доступа к мотивации
            throw new ForbiddenHttpException('У Вас нет доступа к этой странице');
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_by = Yii::$app->user->id;
            $model->motivation_id = $motivation->id;
            if($model->save()) {
                return $this->redirect(['reward/index', 'motivation_id' => $motivation->id]);
            } else {
                //debugmessage($model->errors);
            }
        }
        
        return $this->render('update', [
            'model' => $model,
            'motivation' => $motivation,
            'errors' => $model->errors
        ]);
        
    }

    /**
     * Deletes an existing Reward model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findRewardModel($id);
        $motivation = $this->findModel($model->motivation_id);
        if(!Yii::$app->user->can('updateItem', ['item' => $motivation])) { //Проверка доступа к мотивации
            throw new ForbiddenHttpException('У Вас нет доступа к этой странице');
        }
        
        $model->is_active = 0;
        $model->save();
        //$model->delete();
        return $this->redirect(['reward/index', 'motivation_id' => $motivation->id]);
    }

    /**
     * Finds the Reward model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reward the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findRewardModel($id)
    {
        if (($model = Reward::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
