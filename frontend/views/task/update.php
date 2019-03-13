<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Task */

$this->title = $model->name;;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты мотивации', 'url' => ['motivation/my']];
$this->params['breadcrumbs'][] = ['label' => $motivation->name, 'url' => ['motivation/views', 'id' => $motivation->id]];
$this->params['breadcrumbs'][] = ['label' => 'Список задач', 'url' => ['task/index', 'id' => $motivation->id]];
$this->params['breadcrumbs'][] = "Редактирование";
?>
<div class="task-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
