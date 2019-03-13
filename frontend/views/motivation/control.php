<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use common\models\Constants;

/* @var $this yii\web\View */
/* @var $model common\models\Motivation */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты мотивации', 'url' => ['my']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['motivation/views', 'id' => $model->id]];
$this->params['breadcrumbs'][] = "Управление проектом";
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-3 my-paticipation">
    <div class="box box-primary first-mot-row">
        <div class="box-header with-border">
            <h3 class="box-title">Задачи на модерации</h3>
            <div class="box-body">
                <?php if($taskOnModeration) {?>
                <?php foreach($taskOnModeration as $taskcomp) {?>
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
                          <?=$taskcomp->activity->user->username?>
                      </div>
                      <div>
                        <?php $form = ActiveForm::begin(['options' => ['class' => 'short_form'], 'action' => ['motivation/accepttask', 'id' => $model->id]]) ?>
                          <div class="accept-box">
                              <button type="button" class="btn btn-block btn-success btn-xs sbm_with_type" data-type="1" data-toggle="tooltip" title="Принять выполнение задачи">
                                  <i class="fa fa-check-circle"></i>
                              </button>
                          </div>
                          <div class="accept-box">
                              <button type="button" class="btn btn-block btn-danger btn-xs sbm_with_type" data-type="2" data-toggle="tooltip" title="Отказать в выполнении задачи">
                                  <i class="fa fa-close"></i>
                              </button>
                          </div>
                          <div class="accept-box">
                              <button type="button" class="btn btn-block btn-primary btn-xs" data-toggle="tooltip" title="(В разработке) Перейти к редактированию">
                                  <i class="fa fa-edit"></i> Ред.
                              </button>
                          </div>
                          <input type="hidden" name="sbm_type" value="" />
                          <input type="hidden" name="taskcomp_id" value="<?=$taskcomp->id?>" />
                        <?php ActiveForm::end() ?>
                      </div>
                    </div>
                </div>
                <?php }?>
                <?php } else {?>
                    Нет задач на модерации
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="col-md-3 my-paticipation">
    <div class="box box-primary first-mot-row">
        <div class="box-header with-border">
            <h3 class="box-title">Награды на модерации</h3>
            <div class="box-body">
                <?php if($rewardOnModeration) {?>
                <?php foreach($rewardOnModeration as $rewardcomp) {?>
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
                        <span class="default_exp">-<?=$rewardcomp->money?></span>
                      </span>
                      <div>
                          <?=$rewardcomp->activity->user->username?>
                      </div>
                      <div>
                        <?php $form = ActiveForm::begin(['options' => ['class' => 'short_form'], 'action' => ['motivation/acceptreward', 'id' => $model->id]]) ?>
                          <div class="accept-box">
                              <button type="button" class="btn btn-block btn-success btn-xs sbm_with_type" data-type="1" data-toggle="tooltip" title="Принять награду(вручить)">
                                  <i class="fa fa-check-circle"></i>
                              </button>
                          </div>
                          <div class="accept-box">
                              <button type="button" class="btn btn-block btn-danger btn-xs sbm_with_type" data-type="2" data-toggle="tooltip" title="Отказать в награде">
                                  <i class="fa fa-close"></i>
                              </button>
                          </div>
                          <div class="accept-box">
                              <button type="button" class="btn btn-block btn-primary btn-xs" data-toggle="tooltip" title="(В разработке) Перейти к редактированию">
                                  <i class="fa fa-edit"></i> Ред.
                              </button>
                          </div>
                          <input type="hidden" name="sbm_type" value="" />
                          <input type="hidden" name="rewardcomp_id" value="<?=$rewardcomp->id?>" />
                        <?php ActiveForm::end() ?>
                      </div>
                    </div>
                </div>
                <?php }?>
                <?php } else {?>
                    Нет наград на модерации
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="motivation-view col-md-4">
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
                            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        
                        <?= Html::a('Задачи', ['task/index', 'id' => $model->id], ['class' => 'btn btn-primary btn-left-m']) ?>
                        <?= Html::a('Награды', ['reward/index', 'motivation_id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Уровни', ['motivation-level/index', 'motivation_id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
                                <?=$activity->user->username?>
                            </div>
                        <?php }?>
                    </div>
                <?php } else { ?>
                    <div class="box-body">Нет участников в этом проекте</div>
                <?php }?>
            </div>
        </div>
    </div>
    <?php /* <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Доступные задачи</h3>
        </div>
        <div class="box-body">
            <?php if($tasks) {?>
                <?php foreach($tasks as $task) {?>
                    <div class="col-md-3">
                        <a href="javascript: void(0);" class="popup_link" data-toggle="popover_task" data-trigger="focus" data-placement="auto right" title="Описание задачи">
                            <div class="info-box bg-aqua">
                                <span class="info-box-icon"><i class="fa fa-tasks"></i></span>

                                <div class="info-box-content">
                                  <span class="info-box-text"><?=$task->name?></span>
                                  <div>
                                    <span>Опыт</span>
                                    <span class="default_exp">+<?=$task->default_exp?></span>
                                  </div>
                                  <div>
                                    <span><?=Constants::WORDS_MONEY?></span>
                                    <span class="default_exp">+<?=$task->default_money?></span>
                                  </div>

                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <div class="task-popover-hidden" style="display: none;">
                                <div style="margin-bottom: 10px;">
                                     Тут будет более подробное описание задачи
                                </div>
                                <span class="info-box-text"><?=$task->name?></span>
                                <div>
                                  <span>Опыт</span>
                                  <span class="default_exp">+<?=$task->default_exp?></span>
                                </div>
                                <div>
                                  <span><?=Constants::WORDS_MONEY?></span>
                                  <span class="default_exp">+<?=$task->default_money?></span>
                                </div>
                                <?php if($access['can_select_task']) {?>
                                <div>
                                    <?php $form = ActiveForm::begin(['options' => ['class' => 'short_form'], 'action' => ['motivation/editactivitytask', 'motivation_id' => $model->id, 'task_id' => $task->id]]) ?>
                                        <input type="hidden" name="start_task" value="1" />
                                        <input type="hidden" name="activity_id" value="<?=$useractivity->id?>" />
                                        <button type="button" class="sbm_form btn btn-sm btn-primary"><i class="fa fa-play-circle"></i></button>
                                    <?php ActiveForm::end() ?>
                                </div>
                                <?php }?>
                            </div>
                        </a>
                    </div>
                
                <?php } ?>
            <?php } else {?>
                Задачи ещё не созданы администратором мотивации
            <?php }?>
        </div>
    </div> */ ?>
</div>
