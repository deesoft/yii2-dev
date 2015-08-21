<?php

use dee\angular\NgView;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>
<div class="box box-info box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">{{name}}</h3>
    </div>
    <div class="box-body" style="overflow-x: auto;overflow-y: auto;">
        <code ng-bind-html="content"></code>
    </div>
</div>