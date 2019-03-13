<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace console\controllers;


use Yii;
use yii\console\Controller;
/**
 * Description of TestController
 *
 * @author user
 */
class TestController extends Controller{
    public function actionInit()
    {
        echo "tested";
    }
    
    public function actionIndex()
    {
        echo "tested1";
    }
}
