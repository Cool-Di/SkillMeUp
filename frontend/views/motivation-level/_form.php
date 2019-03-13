<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MotivationLevel */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="box-header with-border">
        <h3 class="box-title">Настройки уровня</h3>
    </div>
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
    <div class="box-body">
        <?= $form->field($model, 'exp', [
                'template' => '{label} <div class="col-sm-10">{input}{error}{hint}</div>'
            ])->input('text', ['class' => 'form-control number-input'])->label(null,['class' => 'col-sm-2 control-label']);
            ?>

        <?= $form->field($model, 'level', [
                'template' => '{label} <div class="col-sm-10">{input}{error}{hint}</div>'
            ])->input('text', ['class' => 'form-control number-input'])->label(null,['class' => 'col-sm-2 control-label']);
            ?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Отменить', Yii::$app->request->referrer, ['class' => 'btn btn-primary btn-left-m']) ?>
    </div>

    <?php ActiveForm::end(); ?>
