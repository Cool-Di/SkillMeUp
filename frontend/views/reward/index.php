<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\RewardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Награды "'. $motivation->name.'"';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты мотивации', 'url' => ['motivation/my']];
$this->params['breadcrumbs'][] = ['label' => $motivation->name, 'url' => ['motivation/views', 'id' => $motivation->id]];
$this->params['breadcrumbs'][] = "Награды";
?>
<div class="motivation-level-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать награду', ['reward/create', 'motivation_id' => $motivation->id], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="col-md-9">
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'is_active',
            'name',
            //'motivation_id',
            'need_money',
            //'need_exp',
            'min_exp',
            'min_level',
            'is_reusable',
            // 'created_by',
            // 'edited_by',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>
<?php Pjax::end(); ?>
    </div>
</div>
