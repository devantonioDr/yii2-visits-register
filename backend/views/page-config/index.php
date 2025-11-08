<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Page Configuration';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-config-index">
    <div class="row">
        <div class="col-md-12">
            <h1><?= Html::encode($this->title) ?></h1>
            <p class="text-muted">Select a configuration section to manage:</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-cog"></i> Site Config
                    </h3>
                </div>
                <div class="box-body">
                    <p>Configure site name, title, and description.</p>
                    <?= Html::a('Manage', ['site-config'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-paint-brush"></i> Brand Colors
                    </h3>
                </div>
                <div class="box-body">
                    <p>Configure brand color scheme.</p>
                    <?= Html::a('Manage', ['brand-colors'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-star"></i> Hero Section
                    </h3>
                </div>
                <div class="box-body">
                    <p>Configure hero section content and media.</p>
                    <?= Html::a('Manage', ['hero-section'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-briefcase"></i> Portfolio Section
                    </h3>
                </div>
                <div class="box-body">
                    <p>Configure portfolio section and images.</p>
                    <?= Html::a('Manage', ['portfolio-section'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-bullhorn"></i> Call to Action
                    </h3>
                </div>
                <div class="box-body">
                    <p>Configure call to action button.</p>
                    <?= Html::a('Manage', ['call-to-action'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-info-circle"></i> About Section
                    </h3>
                </div>
                <div class="box-body">
                    <p>Configure about section and features.</p>
                    <?= Html::a('Manage', ['about-section'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-wrench"></i> Services
                    </h3>
                </div>
                <div class="box-body">
                    <p>Manage services list.</p>
                    <?= Html::a('Manage', ['services'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-footer"></i> Footer Content
                    </h3>
                </div>
                <div class="box-body">
                    <p>Configure footer content and branding.</p>
                    <?= Html::a('Manage', ['footer-content'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-share-alt"></i> Social Links
                    </h3>
                </div>
                <div class="box-body">
                    <p>Manage social media links.</p>
                    <?= Html::a('Manage', ['social-links'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
