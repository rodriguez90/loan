<?php
$items = [['label' => 'Menu', 'options' => ['class' => 'header']]];

if(Yii::$app->authManager->checkAccess(Yii::$app->user->getId(),'loan_list'))
{
    $items[]=['label' => 'Prestamos', 'icon' => 'money', 'url' => ['/loan/index']];
}

if(Yii::$app->authManager->checkAccess(Yii::$app->user->getId(),'payment_list', []))
{
    $items[]=['label' => 'Cuotas', 'icon' => 'credit-card', 'url' => ['/payment/index']];
}

//if(Yii::$app->authManager->checkAccess(Yii::$app->user->getId(),'refinancing'))
//{
//    $items[]=['label' => 'Refinanciar', 'icon' => 'refresh', 'url' => ['/loan/index']];
//}

if(Yii::$app->authManager->checkAccess(Yii::$app->user->getId(),'customer_list'))
{
    $items[]=['label' => 'Clientes', 'icon' => 'users', 'url' => ['/customer/index']];
}
if(Yii::$app->authManager->getAssignment('admin',Yii::$app->user->getId()) ||
    Yii::$app->authManager->getAssignment('Administrador',Yii::$app->user->getId()))
{
    $items[]=['label' => 'Reporte', 'icon' => 'file', 'url' => ['/site/report']];
}
if(Yii::$app->authManager->getAssignment('admin',Yii::$app->user->getId()))
{
    $items[]=['label' => 'Administración', 'icon' => 'cogs', 'url' => ['/user/admin/index']];
}

?>
<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => $items
//                'items' => [
//                    ['label' => 'Menu', 'options' => ['class' => 'header']],

//                    ['label' => 'Prestamos', 'icon' => 'money', 'url' => ['/loan/index']],
//                    ['label' => 'Cobros', 'icon' => 'credit-card', 'url' => ['/payment/index']],
//                    ['label' => 'Clientes', 'icon' => 'users', 'url' => ['/customer/index']],
//                    ['label' => 'Administración', 'icon' => 'cogs', 'url' => ['/user/admin/index']],
//                    [
//                        'label' => 'Administración',
//                        'icon' => 'share',
//                        'url' => '#',
//                        'items' => [
//                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
//                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
//                            [
//                                'label' => 'Level One',
//                                'icon' => 'circle-o',
//                                'url' => '#',
//                                'items' => [
//                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
//                                    [
//                                        'label' => 'Level Two',
//                                        'icon' => 'circle-o',
//                                        'url' => '#',
//                                        'items' => [
//                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
//                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
//                                        ],
//                                    ],
//                                ],
//                            ],
//                        ],
//                    ],
//                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
//                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
//                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],

//                ]
                ,
            ]
        ) ?>

    </section>

</aside>
