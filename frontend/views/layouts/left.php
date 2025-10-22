<?php

use yii\widgets\ActiveForm;
use frontend\models\JceForm;


?>

<aside class="main-sidebar">

  <section class="sidebar">


    <?= dmstr\widgets\Menu::widget(
      [
        'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
        'items' => [
          [
            'label' => 'Analytics Dashboard',
            'icon' => 'bar-chart',
            'url' => ['/analytics-dashboard/index'],
            'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin(),
          ],
          [
            'label' => 'Event Feeder',
            'icon' => 'database',
            'url' => ['/event-feeder/index'],
            'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin(),
          ]

        ]
      ]
    ) ?>

  </section>

</aside>


<style>
  .skin-blue .sidebar-form {
    border: none !important;
  }
</style>