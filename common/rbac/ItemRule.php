<?php
namespace common\rbac;

use Yii;
use yii\rbac\Rule;

/**
 * Проверяем authorID на соответствие с пользователем, переданным через параметры
 */
class ItemRule extends Rule
{
    public $name = 'canUpdateItem';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if(!isset($params['item'])) {
            return false;
        }
        //Даём доступ, если пользователь автор элемента или если пользователь админ
        if($params['item']->owner_id == $user || Yii::$app->user->can('admin')) {
            return true;
        } else {
            return false;
        }
    }
}