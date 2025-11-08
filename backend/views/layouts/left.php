<?php

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
            'visible' => !Yii::$app->user->isGuest,
          ],
          [
            'label' => 'Page Config',
            'icon' => 'cog',
            'url' => ['/page-config/index'],
            'visible' => !Yii::$app->user->isGuest,
            'items' => [
              [
                'label' => 'Site Config',
                'url' => ['/page-config/site-config'],
                'icon' => 'circle-o',
              ],
              [
                'label' => 'Brand Colors',
                'url' => ['/page-config/brand-colors'],
                'icon' => 'circle-o',
              ],
              [
                'label' => 'Hero Section',
                'url' => ['/page-config/hero-section'],
                'icon' => 'circle-o',
              ],
              [
                'label' => 'Portfolio Section',
                'url' => ['/page-config/portfolio-section'],
                'icon' => 'circle-o',
              ],
              [
                'label' => 'Call to Action',
                'url' => ['/page-config/call-to-action'],
                'icon' => 'circle-o',
              ],
              [
                'label' => 'About Section',
                'url' => ['/page-config/about-section'],
                'icon' => 'circle-o',
              ],
              [
                'label' => 'Services',
                'url' => ['/page-config/services'],
                'icon' => 'circle-o',
              ],
              [
                'label' => 'Footer Content',
                'url' => ['/page-config/footer-content'],
                'icon' => 'circle-o',
              ],
              [
                'label' => 'Social Links',
                'url' => ['/page-config/social-links'],
                'icon' => 'circle-o',
              ],
            ],
          ],
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

