<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
  </div><!-- /.box-header -->
  <div class="box-body">
    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email',
            'status',
            'created_at:date',
            'updated_at:datetime',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{views}&nbsp;&nbsp;{update}&nbsp;&nbsp;{permit}&nbsp;&nbsp;{delete}',
                'buttons' =>
                    [
                        'permit' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-wrench"></span>', Url::to(['/permit/user/views', 'id' => $model->id]), [
                                'title' => Yii::t('yii', 'Change user role')
                            ]); },
                    ]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
  </div><!-- /.box-body -->
</div><!-- /.box -->
