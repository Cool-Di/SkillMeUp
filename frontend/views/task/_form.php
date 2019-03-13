<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="box-header with-border">
        <h3 class="box-title">Настройки задачи</h3>
    </div>
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
    <div class="box-body">
        <?= $form->field($model, 'name', [
            'template' => '{label} <div class="col-sm-10">{input}{error}{hint}</div>'
        ])->input('text', ['placeholder' => "Отображаемый текст задачи"])->label(null,['class' => 'col-sm-2 control-label']);
        ?>

        <?= $form->field($model, 'default_money', [
            'template' => '{label} <div class="col-sm-10">{input}{error}{hint}</div>'
        ])->input('text', ['class' => 'form-control number-input'])->label(null,['class' => 'col-sm-2 control-label']);
        ?>
        
        <?= $form->field($model, 'default_exp', [
            'template' => '{label} <div class="col-sm-10">{input}{error}{hint}</div>'
        ])->input('text', ['class' => 'form-control number-input'])->label(null,['class' => 'col-sm-2 control-label']);
        ?>
        
        <?= $form->field($model, 'min_exp', [
            'template' => '{label} <div class="col-sm-10">{input}{error}{hint}</div>'
        ])->input('text', ['class' => 'form-control number-input'])->label(null,['class' => 'col-sm-2 control-label']);
        ?>

        <?= $form->field($model, 'is_reusable', [
            'template' => '<div class="col-sm-offset-2 col-sm-10"><div class="checkbox">{input}</div>{error}{hint}</div>'
        ])->checkbox([], true)->label(null,['class' => 'col-sm-2 control-label']);
        ?>
    </div>
    <div class="box-footer">
        <?= Html::a( 'Отмена', Yii::$app->request->referrer, ['class' => 'btn btn-default'])?>
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => 'btn btn-info pull-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php if(!empty($errors)) {?>
    <div class="box-footer">
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-ban"></i>Ошибка сохранения задачи</h4>
            <?php foreach($errors as $error) {?>
                <?php foreach($error as $singlerror) {?>
                    <div>
                        <?=$singlerror?>
                    </div>
                <?php }?>
            <?php } ?>
          </div>
    </div>
<?php } ?>

