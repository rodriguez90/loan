<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 15/11/2018
 * Time: 11:09
 */

namespace app\rbac;
use yii\rbac\Item;
use yii\rbac\Rule;
use app\models\Payment;


class PaymentRule extends Rule
{
    public $name = 'isCollector';

    public function execute($user, $item, $params)
    {
        $result = false;
        // TODO: Implement execute() method.
//        var_dump($item);die;
        if( isset($params['payment']))
        {
            if($params['payment']->collector_id == $user
                || \Yii::$app->authManager->getAssignment('admin', $user)
                || \Yii::$app->authManager->getAssignment('Administrador', $user))
            {
                $result = true;
            }
        }
        return $result;
    }
}