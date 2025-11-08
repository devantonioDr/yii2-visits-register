<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $directoryAsset string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">ADM</span><span class="logo-lg">' . Yii::$app->params['appName'] . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
      
        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <?php if (!Yii::$app->user->isGuest): ?>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= Yii::$app->user->identity->username; ?> </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
                            <p>
                                <?= Yii::$app->user->identity->username; ?>
                                <?php if (Yii::$app->user->identity->created_at): ?>
                                    <small><?= date('d/m/Y', Yii::$app->user->identity->created_at); ?> </small>
                                <?php endif; ?>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

</header>
<style>
  .skin-blue .main-header .navbar,
  .skin-blue .main-header li.user-header {
    background-color: #222d32 !important;
  }

  .logo {
    background-color: #222d32 !important;
  }
  .sidebar-toggle{
    background-color: #222d32 !important;
  }
</style>

