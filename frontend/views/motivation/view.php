<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use common\models\Constants;

/* @var $this yii\web\View */
/* @var $model common\models\Motivation */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты мотивации', 'url' => ['my']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-3 my-paticipation">
        <div class="box box-primary first-mot-row">
        <?php if($useractivity) {?>
            <div class="box-body box-profile">
                <div class="box-header with-border">
                    <h3 class="box-title">Ваша активность</h3>
                </div>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <b>Опыт</b> <a class="pull-right"><?=$useractivity->exp?></a>
                    </li>
                    <li class="list-group-item">
                      <b><?=Constants::WORDS_MONEY?></b> <a class="pull-right"><?=$useractivity->money?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Уровень</b> <a class="pull-right"><?=$useractivity->currentLevel?></a>
                    </li>
                </ul>
              <?php /*<a href="#" class="btn btn-primary btn-block"><b>Действовать</b></a>*/?>
            </div>
            <?php if(Yii::$app->session->hasFlash('activity_started')) {?>
                <div class="box-body">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Начало!</h4>
                        <?= Yii::$app->session->getFlash('activity_started');?>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="box-header with-border">
                <h3 class="box-title">Ваша активность</h3>
            </div>
            <div class="box-footer">
                <?php $form = ActiveForm::begin(['options' => ['class' => 'short_form', 'name' => 'short_form']]) ?>
                    <?php //= $form->field($model, 'name')->label('Имя')?>
                    <input type="hidden" name="start_activity" value="1" />
                    <button type="button" class="sbm_form btn btn-primary">Начать выполнение мотивации</button>
                <?php ActiveForm::end() ?>
            </div>
        <?php }?>
        </div>
        <?php if($access['can_select_task']) {?>
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Выполненные задачи</h3><span> <a href="javascript:void(0);" title="В разработке">(Все)</a></span>
            </div>
            <div class="box-body">
                <?php if($useractivity->taskcomplite) {?>
                    <?php foreach($useractivity->taskcomplite as $taskcomp) {?>
                        <div class="info-box bg-<?=$taskcomp->status_style?>">
                            <span class="info-box-icon"><i class="fa <?=$taskcomp->status_icon?>"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text"><?=$taskcomp->task->name?></span>
                              <span>
                                <span>Опыт</span>
                                <span class="default_exp">+<?=$taskcomp->exp?></span>
                              </span>
                              <span>
                                <span><?=Constants::WORDS_MONEY?></span>
                                <span class="default_exp">+<?=$taskcomp->money?></span>
                              </span>
                              <div>
                                  <?=$taskcomp->status_help?>
                              </div>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    <?php }?>
                <?php } else {?>
                    Нет выполненных задач.
                <?php } ?>
            </div>
        </div>
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Полученные награды</h3><span> <a href="javascript:void(0);" title="В разработке">(Все)</a></span>
            </div>
            <div class="box-body">
                <?php if($useractivity->rewardcomplite) {?>
                    <?php foreach($useractivity->rewardcomplite as $rewardcomp) {?>
                        <div class="info-box bg-<?=$rewardcomp->status_style?>">
                            <span class="info-box-icon"><i class="fa <?=$rewardcomp->status_icon?>"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text"><?=$rewardcomp->reward->name?></span>
                              <span>
                                <span>Опыт</span>
                                <span class="default_exp"><?=$rewardcomp->exp?></span>
                              </span>
                              <span>
                                <span><?=Constants::WORDS_MONEY?></span>
                                <span class="default_exp"><?=$rewardcomp->money?></span>
                              </span>
                              <div>
                                  <?=$rewardcomp->status_help?>
                              </div>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    <?php }?>
                <?php } else {?>
                    Нет выполненных задач.
                <?php } ?>
            </div>
        </div>
        <?php }?>
    </div>
    <div class="motivation-view col-md-9">
        <div class="nav-tabs-custom first-mot-row">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-project" data-toggle="tab" aria-expanded="true">Проект</a></li>
                <li class=""><a href="#tab-users" data-toggle="tab" aria-expanded="false">Участники</a></li>
            </ul>
            <div class="tab-content">
                <div id="tab-project" class="tab-pane active">
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
                    <?php if($access["can_control"]) {?>
                        <div class="mot-box-btn">

                            <?php //= Html::a('Задачи', ['task/index', 'id' => $model->id], ['class' => 'btn btn-primary btn-left-m']) ?>
                            <?= Html::a('Управление', ['motivation/control', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        </div>
                    <?php } ?>
                </div>
                <div id="tab-users" class="tab-pane">
                    <?php if($activities) {?>
                        <div class="box-header with-border">
                            <h3 class="box-title">Список участников мотивации</h3>
                        </div>
                        <div class="box-body">
                            <?php foreach($activities as $activity) {?>
                                <div>
                                    <?=$activity['username']?> (<?=intval($activity['level'])?> лвл)
                                </div>
                            <?php }?>
                        </div>
                    <?php } else { ?>
                        <div class="box-body">Нет участников в этом проекте</div>
                    <?php }?>
                </div>
            </div>
        </div>
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Доступные задачи</h3>
            </div>
            <div class="box-body">
                <?php if($tasks) {?>
                    <?php foreach($tasks as $task) {?>
                        <?php $task->checkExp($useractivity->exp)?>
                        <div class="col-md-3">
                            <a href="javascript: void(0);" class="popup_link" data-toggle="popover_task" data-trigger="focus" data-placement="auto right" title="Описание задачи">
                                <div class="info-box bg-<?=$task->box_color?>">
                                    <span class="info-box-icon"><i class="fa <?=$task->getBoxIcon()?>"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text"><?=$task->name?></span>
                                      <div style="float: left">
                                        <div>
                                          <span>Опыт</span>
                                          <span class="default_exp">+<?=$task->default_exp?></span>
                                        </div>
                                        <div>
                                          <span><?=Constants::WORDS_MONEY?></span>
                                          <span class="default_exp">+<?=$task->default_money?></span>
                                        </div>
                                          <?//debugmessage($task->taskcomplite)?>
                                        <?php if($task->cant_repeat) {?>
                                            <div>
                                                <span>Уже выполнено</span>
                                            </div>
                                        <?php } elseif(!$task->enough_exp) {?>
                                            <div>
                                              <span>Минимальный опыт</span>
                                              <span class="default_exp"><?=$task->min_exp?></span>
                                            </div>
                                        <?php }?>
                                      </div>
                                      <?php if($access['can_select_task'] && $task->min_exp <= $useractivity->exp && !$task->cant_repeat) {?>
                                        <div class="stast-box" style="float: left; margin: 10px; display: none;">
                                            <?php $form = ActiveForm::begin(['options' => ['class' => 'short_form'], 'action' => ['motivation/editactivitytask', 'motivation_id' => $model->id, 'task_id' => $task->id]]) ?>
                                                <input type="hidden" name="start_task" value="1" />
                                                <input type="hidden" name="activity_id" value="<?=$useractivity->id?>" />
                                                <button type="button" class="sbm_form btn btn-sm btn-primary"><i class="fa fa-play-circle"></i></button>
                                            <?php ActiveForm::end() ?>
                                        </div>
                                      <?php }?>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <div class="task-popover-hidden" style="display: none;">
                                    <span class="info-box-text"><?=$task->name?></span>
                                    <div class="smal_desc"><?=$task->is_reusable ? "многоразовая" : "одноразовая"?></div>
                                    <?php if($task->enough_exp) {?>
                                    <div>
                                      <span>Опыт</span>
                                      <span class="default_exp">+<?=$task->default_exp?></span>
                                    </div>
                                    <div>
                                      <span><?=Constants::WORDS_MONEY?></span>
                                      <span class="default_exp">+<?=$task->default_money?></span>
                                    </div>
                                    <?php } else { ?>
                                    <div>
                                      <span>Минимальный опыт</span>
                                      <span class="default_exp"><?=$task->min_exp?></span>
                                    </div>
                                    <?php } ?>
                                </div>
                            </a>
                        </div>

                    <?php } ?>
                <?php } else {?>
                    Задачи ещё не созданы администратором мотивации
                <?php }?>
            </div>
        </div>
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Доступные награды</h3>
            </div>
            <div class="box-body">
                <?php if($rewards) {?>
                    <?php foreach($rewards as $item) {?>
                        <?php $item->checkExp($useractivity)?>
                        <div class="col-md-3">
                            <a href="javascript: void(0);" class="popup_link" data-toggle="popover_task" data-trigger="focus" data-placement="auto right" title="Описание награды">
                                <div class="info-box bg-<?=$item->box_color?>">
                                    <span class="info-box-icon"><i class="fa fa-tasks"></i></span>

                                    <div class="info-box-content">
                                      <span class="info-box-text"><?=$item->name?></span>
                                      <div style="float: left">
                                        <div>
                                            <span>Опыт</span>
                                            <span class="default_exp"><?=$item->min_exp?></span>
                                        </div>
                                        <div>
                                            <span><?=Constants::WORDS_MONEY?> </span>
                                            <span class="default_exp"><?=$item->need_money?></span>
                                        </div>
                                        <?php if($item->cant_repeat) {?>
                                            <div>
                                                <span>Уже выполнено</span>
                                            </div>
                                         <?php } ?>
                                      </div>
                                        <?//debugmessage($item->rewardcomplite)?>
                                      <?php if($access['can_select_reward'] && $item->enough_points && !$item->cant_repeat) {?>
                                        <div class="stast-box" style="float: left; margin: 10px; display: none;">
                                            <?php $form = ActiveForm::begin(['options' => ['class' => 'short_form'], 'action' => ['motivation/editactivityreward', 'motivation_id' => $model->id, 'reward_id' => $item->id]]) ?>
                                                <input type="hidden" name="get_reward" value="1" />
                                                <input type="hidden" name="activity_id" value="<?=$useractivity->id?>" />
                                                <button type="button" class="sbm_form btn btn-sm btn-primary"><i class="fa fa-play-circle"></i></button>
                                            <?php ActiveForm::end() ?>
                                        </div>
                                      <?php }?>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <div class="task-popover-hidden" style="display: none;">
                                    <span class="info-box-text"><?=$item->name?></span>
                                    <div class="smal_desc"><?=$item->is_reusable ? "многоразовая" : "одноразовая"?></div>
                                    <?php if($item->enough_points) {?>
                                    <div>
                                      <span>Опыт</span>
                                      <span class="default_exp"><?=$item->min_exp?></span>
                                    </div>
                                    <div>
                                      <span><?=Constants::WORDS_MONEY?></span>
                                      <span class="default_exp"><?=$item->need_money?></span>
                                    </div>
                                    <?php } ?>
                                </div>
                            </a>
                        </div>

                    <?php } ?>
                <?php } else {?>
                    Задачи ещё не созданы администратором мотивации
                <?php }?>
            </div>
        </div>
    </div>
</div>