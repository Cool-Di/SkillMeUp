<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\MotivationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Учавствую в мотивационных проектах';
$this->params['breadcrumbs'][] = "Мои проекты";
?>
<div class="box box-primary">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'name',
                    'format' => 'raw',
                    'label' => 'Название',
                    'value' => function ($data) {
                        return Html::a(Html::encode($data->name), Url::to(['views', 'id' => $data->id]));
                    },
                ],
                [
                    'attribute' => 'owner.username',
                    'format' => 'text',
                    'label' => 'Автор',
                ],
                'created_at',
                'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
