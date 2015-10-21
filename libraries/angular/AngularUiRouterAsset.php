<?php

namespace dee\angular;

/**
 * AngularRouteAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class AngularUiRouterAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/angular-ui-router/release';

    /**
     * @inheritdoc
     */
    public $js = [
        'angular-ui-router.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'dee\angular\AngularAsset'
    ];
}