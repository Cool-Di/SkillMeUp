<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div>
    Доступ запрещён<br />
    Для авторизации перейдите на страницу <?= Html::a('Log in', ['site/login']) ?>
</div>
