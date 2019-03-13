<?php
namespace common\widgets;

use Yii;
use yii\base\Widget;
use common\models\Notice;

class NoticeWidget extends Widget
{
    //public $message; //-таким способом можно передавать переменные в виджет. В шаблоне ['message' => 'Good morning'], в метадах класса виджет $this->message

    public function init()
    {
        parent::init();

    }

    public function run()
    {
        $notices = Notice::find()->where(['user_id' => Yii::$app->user->id, 'is_read' => false])->asArray()->all();
        //debugmessage($notices);
        return $this->render('notice_header', [
            'notices' => $notices
        ]);
    }
}