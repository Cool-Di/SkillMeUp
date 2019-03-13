<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MotivationLevel */

$this->title = $motivation->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты мотивации', 'url' => ['motivation/my']];
$this->params['breadcrumbs'][] = ['label' => $motivation->name, 'url' => ['motivation/views', 'id' => $motivation->id]];
$this->params['breadcrumbs'][] = ['label' => 'Уровни', 'url' => ['motivation-level/index', 'motivation_id' => $motivation->id]];
$this->params['breadcrumbs'][] = "Редактировать уровень";
?>
<div class="motivation-level-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
