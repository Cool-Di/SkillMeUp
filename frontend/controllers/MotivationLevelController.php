<?php

namespace frontend\controllers;

use Yii;
use common\models\MotivationLevel;
use common\models\MotivationLevelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

use frontend\controllers\MotivationBaseController;
/**
 * MotivationLevelController implements the CRUD actions for MotivationLevel model.
 */
class MotivationLevelController extends MotivationBaseController
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
     * Lists all MotivationLevel models.
     * @return mixed
     */
    public function actionIndex($motivation_id)
    {
        
        $motivation = $this->findModel($motivation_id);
        if(!Yii::$app->user->can('updateItem', ['item' => $motivation])) { //Проверка доступа к мотивации
            throw new ForbiddenHttpException('У Вас нет доступа к этой странице');
        } 
        //debugmessage($motivation);
        $searchModel = new MotivationLevelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $motivation_id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'motivation' => $motivation,
        ]);
        
    }

    /**
     * Displays a single MotivationLevel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($motivation_id)
    {
        return $this->render('views', [
            'model' => $this->findModelLevel($motivation_id),
        ]);
    }

    /**
     * Creates a new MotivationLevel model.
     * If creation is successful, the browser will be redirected to the 'views' page.
     * @return mixed
     */
    public function actionCreate($motivation_id)
    {
        $motivation = $this->findModel($motivation_id);
        
        if(!Yii::$app->user->can('updateItem', ['item' => $motivation])) { //Проверка доступа к мотивации
            throw new ForbiddenHttpException('У Вас нет доступа к этой странице');
        }
        
        $model = new MotivationLevel();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = Yii::$app->user->id;
            $model->edited_by = Yii::$app->user->id;
            $model->motivation_id = $motivation_id;
            if($model->save()) {
                return $this->redirect(['motivation-level/index', 'motivation_id' => $motivation->id]);
            } else {
                debugmessage($model->errors);
            }
        } 
        return $this->render('create', [
            'model' => $model,
            'motivation' => $motivation,
            'errors' => $model->errors
        ]);
    }

    /**
     * Updates an existing MotivationLevel model.
     * If update is successful, the browser will be redirected to the 'views' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        
        $model = $this->findModelLevel($id);
        
        $motivation = $this->findModel($model->motivation_id);
        
        if(!Yii::$app->user->can('updateItem', ['item' => $motivation])) { //Проверка доступа к мотивации
            throw new ForbiddenHttpException('У Вас нет доступа к этой странице');
        }
        
        if ($model->load(Yii::$app->request->post())) {
            $model->edited_by = Yii::$app->user->id;
            $model->motivation_id = $motivation->id;
            if($model->save()) {
                return $this->redirect(['motivation-level/index', 'motivation_id' => $motivation->id]);
            } else {
                debugmessage($model->errors);
            }
        }
        
        return $this->render('update', [
            'model' => $model,
            'motivation' => $motivation,
            'errors' => $model->errors
        ]);
        
    }

    /**
     * Deletes an existing MotivationLevel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModelLevel($id);
        $motivation = $this->findModel($model->motivation_id);
        if(!Yii::$app->user->can('updateItem', ['item' => $motivation])) { //Проверка доступа к мотивации
            throw new ForbiddenHttpException('У Вас нет доступа к этой странице');
        }
        
        $model->is_active = 0;
        $model->save();
        //$model->delete();
        return $this->redirect(['motivation-level/index', 'motivation_id' => $motivation->id]);
    }

    /**
     * Finds the MotivationLevel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MotivationLevel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelLevel($id)
    {
        if (($model = MotivationLevel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
