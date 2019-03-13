<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Task */

$this->title = $motivation->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты мотивации', 'url' => ['motivation/my']];
$this->params['breadcrumbs'][] = ['label' => $motivation->name, 'url' => ['motivation/views', 'id' => $motivation->id]];
$this->params['breadcrumbs'][] = ['label' => 'Список задач', 'url' => ['task/index', 'id' => $motivation->id]];
$this->params['breadcrumbs'][] = "Новая задача";
?>
<div class="col-md-7">
    <div class="task-create box box-info">

        <?= $this->render('_form', compact('model', 'errors')) ?>

    </div>
</div>
