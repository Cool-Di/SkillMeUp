<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список задач для проекта "'. $motivation->name.'"';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты мотивации', 'url' => ['motivation/my']];
$this->params['breadcrumbs'][] = ['label' => $motivation->name, 'url' => ['motivation/views', 'id' => $motivation->id]];
$this->params['breadcrumbs'][] = "Список задач";
?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Список задач</h3>
    </div>
    <div class="box-footer">
        <?= Html::a('Добавить задачу', ['task/create', 'id' => $motivation->id], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="box-body">
        <?php Pjax::begin(); ?>    <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'id',
                    //'is_active',
                    'name',
                    //'created_by',
                    //'motivation_id',
                    'default_money',
                    'default_exp',
                    'min_exp',
                    'is_reusable',
                    // 'created_at',
                    // 'updated_at',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
