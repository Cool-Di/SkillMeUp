<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        
        $author = $auth->getRole('user');
        //$updatePost = $auth->getPermission('updateMotivation');
        
        // add the rule
        $rule = new \common\rbac\ItemRule;

        $updateOwnPost = $auth->getPermission('updateItem');
        $updateOwnPost->ruleName = $rule->name;
        $auth->update('updateItem', $updateOwnPost);

        
        // "updateOwnPost" будет использоваться из "updatePost"
        //$auth->addChild($updateOwnPost, $updatePost);

        // разрешаем "автору" обновлять его посты
        //$auth->addChild($author, $updateOwnPost);

    }
}