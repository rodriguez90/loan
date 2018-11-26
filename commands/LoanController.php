<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 04/11/2018
 * Time: 5:35
 */


namespace app\commands;

use Da\User\Command\CreateController;
use yii\console\Controller;
use yii\console\ExitCode;


class LoanController extends Controller
{
    /**
     * @return int
     */
    public function actionGenerateRootUser()
    {
        $createController = new CreateController('create','user');
        $email = 'root@test.com';
        $username = 'root';
        $year = date(strtotime("Y"));
        $password = 'root*'.$year;
        $role = 'Administrador';
        $createController->actionIndex($email,$username, $password, $role);

        return ExitCode::OK;
    }

    public function actionGeneratePermision()
    {
        // FIXME: Check this
        return ExitCode::OK;
    }

    public function actionDefaultAssingPermision()
    {
        // FIXME: Check this
        return ExitCode::OK;
    }


}