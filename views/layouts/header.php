<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */


 $user  = null;

 if(!Yii::$app->user->isGuest)
     $user = Yii::$app->user->identity;

?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <?php
                    if(!Yii::$app->user->isGuest)
                    {
                        echo Html::beginTag('li', ['class'=>'dropdown user user-menu']);
                            echo Html::beginTag('a', null ,['class'=>'dropdown-toggle', 'data-toggle'=>"dropdown"]);
                                echo Html::tag('span', $user->username ,['class'=>'hidden-xs']);
                            echo Html::endTag('a');
                            echo Html::beginTag('ul', ['class'=>'dropdown-menu']);
                                echo Html::beginTag('li', ['class'=>'user-header']);
                                    echo Html::beginTag('p', ['class'=>'user-header']);
                                        echo Html::encode($user->username);
                                    echo Html::endTag('p');
                                echo Html::endTag('li');

                                echo Html::beginTag('li', ['class'=>'user-footer']);
                                echo Html::beginTag('div', ['class'=>'pull-left']);
                                    echo Html::a('Perfil', \yii\helpers\Url::to(['/user/settings/profile']));
                                echo Html::endTag('div');

                                echo Html::beginTag('div', ['class'=>'pull-right']);
                                    echo Html::a(
                                        'Salir',
                                        ['/user/security/logout'],
                                        ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                    );
                                echo Html::endTag('div');

                                echo Html::endTag('li');
                            echo Html::endTag('ul');
                        echo Html::endTag('li');

//                        var_dump($html) ;die;
                    }
                ?>

                <!-- User Account: style can be found in dropdown.less -->
<!--                <li>-->
<!--                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>-->
<!--                </li>-->
            </ul>
        </div>
    </nav>
</header>
