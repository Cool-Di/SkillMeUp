<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Motivation */

$this->title = 'Create Motivation';
$this->params['breadcrumbs'][] = ['label' => 'Motivations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="motivation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
