<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Reward */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box-header with-border">
    <h3 class="box-title">Настройки награды</h3>
</div>
<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
<div class="box-body">
    <?= $form->field($model, 'name', [
            'template' => '{label} <div class="col-sm-10">{input}{error}{hint}</div>'
        ])->input('text', ['placeholder' => "Отображаемый текст награды"])->label(null,['class' => 'col-sm-2 control-label']);
        ?>
    
    <?= $form->field($model, 'need_money', [
            'template' => '{label} <div class="col-sm-10">{input}{error}{hint}</div>'
        ])->input('text', ['class' => 'form-control number-input'])->label(null,['class' => 'col-sm-2 control-label']);
        ?>
    
    <?= $form->field($model, 'min_exp', [
            'template' => '{label} <div class="col-sm-10">{input}{error}{hint}</div>'
        ])->input('text', ['class' => 'form-control number-input'])->label(null,['class' => 'col-sm-2 control-label']);
        ?>
    <div>
        или
    </div>
    
    <?= $form->field($model, 'min_level', [
            'template' => '{label} <div class="col-sm-10">{input}{error}{hint}</div>'
        ])->input('text', ['class' => 'form-control number-input'])->label(null,['class' => 'col-sm-2 control-label']);
        ?>
    <?= $form->field($model, 'is_reusable', [
        'template' => '<div class="col-sm-offset-2 col-sm-10"><div class="checkbox">{input}</div>{error}{hint}</div>'
    ])->checkbox([], true)->label(null,['class' => 'col-sm-2 control-label']);
    ?>
</div>
<div class="box-footer">
    <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?= Html::a('Отменить', Yii::$app->request->referrer, ['class' => 'btn btn-primary btn-left-m']) ?>
</div>

<?php ActiveForm::end(); ?>
