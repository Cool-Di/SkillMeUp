<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Motivation */

$this->title = 'Редактирование проекта: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты мотивации', 'url' => ['my']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['views', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="motivation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
