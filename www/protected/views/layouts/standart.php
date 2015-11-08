<?php
/**
 * @var $app CWebApplication
 * @var $this CController
 * @var $content string rendered template as HTML markup
 */
$app = Yii::app();
 ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="utf-8">
    <base href="<?php echo $app->baseUrl ?>/">
     
        <link rel="stylesheet" type="text/css" href="<?php echo $app->baseUrl; ?>/css/style.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $app->baseUrl; ?>/css/bootstrap-theme.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $app->baseUrl; ?>/css/bootstrap.css">
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo $app->baseUrl; ?>/css/ie.css" media="screen, projection"/>
        <![endif]-->
        <title><?php echo $this->pageTitle;   ?></title>
     

    <!-- blueprint CSS framework -->
    <!--
    -->
<!--    <link rel="stylesheet" type="text/css" href="--><?php //echo $app->baseUrl ?><!--/css/screen.css" media="screen, projection" />-->
    <link rel="stylesheet" type="text/css" href="<?php echo $app->baseUrl ?>/css/print.css" media="print" />

<!--    <link rel="stylesheet" type="text/css" href="--><?php //echo $app->baseUrl ?><!--/css/main.css" />-->
    <link rel="stylesheet" type="text/css" href="<?php echo $app->baseUrl ?>/css/form.css" />

</head>
<body>
<div class="container" id="page">
    <div class="logo container np">
        <div class="col-xs-12 logo-div ">
            <div class="col-xs-2 logo-img">
                <img src="img/logo.png" class="">
            </div>
            <div class="col-xs-7">
                <p class="m1e"> Last Price: <b>279.568 USD</b> Low: <b>277 USD</b> High: <b>282.9869 USD</b></p>

                <p class="m1e"> Volume: <b>5275 BTC / 1476257 USD</b> Server Time: <b><?php echo date('d.m.Y H:i');?></b></p>
            </div>
            <div class="col-xs-3">
                <form>
                    <input type="text" name="email" class="mt1e" placeholder="E-mail">
                    <input type="password" name="email" class="mt1e" placeholder="Пароль">
                </form>
                <p class="mt1e">
                    <?php  if ($app->user->isGuest) {  ?>
                        <a href="<?php echo $app->createUrl('user/login');  ?>">Войти</a>&nbsp;|&nbsp;
                    <?php } else { ?>
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo $app->user->id  ?>">
                        <a href="<?php echo $app->createUrl('user/logout')  ?>"><?php echo $app->user->name  ?></a>&nbsp;|&nbsp;
                    <?php  }  ?>

                    <a href="<?php echo $app->createUrl('user/registration')  ?>">Регистрация</a>&nbsp;|&nbsp;
                    <a href="#">Забыл пароль</a>
                </p>
            </div>
        </div>

            <?php  if ($this->breadcrumbs) {
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ), true);
            } ?>

        <div class="col-xs-12 menu1 np">
            <menu class="col-xs-12 np main-menu">
                <li><a href="#">Торги</a></li>
                <li><a href="#">Новости</a></li>
                <li><a href="#">Правила</a></li>
                <li><a href="#">FuQ</a></li>
                <li><a href="/gii">Gii</a></li>
            </menu>
        </div>

    </div>
    <div class="content container">
        <div class="col-xs-12">
            <?php echo $content?>
        </div>
    </div>
    <div id="footer" class="footer container">

            Copyright &copy; <?php echo date('Y');   ?> Shikon & X-iLeR.<br/>
            All Rights Reserved.<br/>
    </div>
</div>
</body>
</html>