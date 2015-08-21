<?php

use yii\web\View;

//use yii\helpers\Html;

/* @var $this View */
?>
<div class="box box-solid box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Add Route(s)</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" ng-click="cancel()"><span class="fa fa-remove"></span></button>
        </div>
    </div>
    <div class="box-body">
        <div class="form-group">
            <label class="">Route (sparate with coma)</label>
            <textarea class="form-control" ng-model="route"></textarea>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" ng-click="ok()"><span class="fa fa-plus"></span></button>
            <button class="btn btn-danger"><span class="fa fa-remove"></span></button>
        </div>
    </div>
</div>