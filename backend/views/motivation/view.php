<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Motivation */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты мотивации', 'url' => ['my']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="motivation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [                      // the owner name of the model
                'label' => 'Автор',
                'value' => $model->owner->username,
            ],
            [             
                'label' => 'Дата создания',
                'value' => $model->created_at,
            ],
            [             
                'label' => 'Дата редактирования',
                'value' => $model->updated_at,
            ],
        ],
    ]) ?>

</div>
