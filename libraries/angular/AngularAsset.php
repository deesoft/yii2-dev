<?php

namespace dee\angular;

/**
 * AngularAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class AngularAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/angular';

    /**
     * @inheritdoc
     */
    public $js = [
        'angular.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public static $assetMap = [
        'ui.bootstrap' => 'dee\angular\AngularBootstrapAsset',
        'dee.ui' => 'dee\angular\DeeAngularUiAsset',
        'dee.rest' => 'dee\angular\DeeAngularRestAsset',
        'ngRoute' => 'dee\angular\AngularRouteAsset',
        'ngResource' => 'dee\angular\AngularResourceAsset',
        'ngAnimate' => 'dee\angular\AngularAnimateAsset',
        'ngAria' => 'dee\angular\AngularAnimateAsset',
        'ngTouch' => 'dee\angular\AngularAnimateAsset',
        'validation' => 'dee\angular\AngularValidationAsset',
        'validation.rule' => 'dee\angular\AngularValidationAsset',
        'ui.router' => 'dee\angular\AngularUiRouterAsset'
    ];
}