<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\MotivationLevelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Настройка уровней "'. $motivation->name.'"';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты мотивации', 'url' => ['motivation/my']];
$this->params['breadcrumbs'][] = ['label' => $motivation->name, 'url' => ['motivation/views', 'id' => $motivation->id]];
$this->params['breadcrumbs'][] = "Уровни";
?>
<div class="motivation-level-index">
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Новый уровень', ['motivation-level/create', 'motivation_id' => $motivation->id], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="col-md-3">
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'motivation_id',
            'exp',
            'level',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>
<?php Pjax::end(); ?>
    </div>
</div>
