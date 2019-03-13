<?php

use yii\helpers\Html;

?>
<? if(!empty($notices)) {?>
<li class="dropdown notifications-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning">10</span>
    </a>
    <ul class="dropdown-menu">
        <!--<li class="header">У вас <?/*=count($notices)*/?> notifications</li>-->
        <li>
            <!-- inner menu: contains the actual data -->
            <ul class="menu">
                <? foreach($notices as $notice) {?>
                    <li>
                        <a href="#">
                            <i class="fa fa-warning text-yellow"></i> <?=$notice["text"]?>
                        </a>
                    </li>
                <?}?>
            </ul>
        </li>
        <li class="footer"><a href="#">Посмотреть все</a></li>
    </ul>
</li>
<?}?>