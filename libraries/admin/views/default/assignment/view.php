<?php

use dee\angular\NgView;

/* @var $this yii\web\View */
/* @var $widget NgView */
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('rbac-admin', 'Assignment') ?></h3>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="box-body form-horizontal">
                        <div class="form-group" >
                            <label class="col-sm-3 control-label"><?= Yii::t('rbac-admin', 'Username') ?></label>
                            <div class="col-sm-9">
                                <p class="form-control-static" ng-bind="model.username"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="padding: 30px;">
                        <alert ng-repeat="alert in alerts" type="{{alert.type}}" dismiss-on-timeout="{{alert.time}}"
                               close="closeAlert($index)">{{alert.msg}}</alert>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-6">
        <div class="box box-success box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('rbac-admin', 'Assigned') ?>:</h3>
                <div class="box-tools pull-right">
                    <div class="has-feedback">
                        <input type="text" class="form-control input-sm"
                               ng-model="filter.assignments" placeholder="Search..."
                               ng-change="applyFilter('assignments')">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                </div>
            </div>
            <div class="box-body no-padding">
                <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button class="btn btn-default btn-sm" ng-click="clickAll('assignments')">
                        <i class="fa" ng-class="{'fa-check':checkAll.assignments,'fa-square-o':!checkAll.assignments}"></i>
                    </button>
                    <button class="btn btn-default btn-sm" ng-click="clickRevoke()">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                    <div class="pull-right">
                        Page {{pagination.assignments.page + 1}} of {{pagination.assignments.total}}
                        <div class="btn-group">
                            <button class="btn btn-default btn-sm" ng-click="clickPrev('assignments');"
                                    ng-class="{disabled:pagination.assignments.page <= 0}">
                                <i class="fa fa-chevron-left"></i></button>
                            <button class="btn btn-default btn-sm" ng-click="clickNext('assignments');"
                                    ng-class="{disabled:pagination.assignments.page >= pagination.assignments.total - 1}">
                                <i class="fa fa-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
                <div table-responsive mailbox-messages>
                    <table class="table table-hover">
                        <tbody>
                            <tr ng-repeat="item in displayed.assignments">
                                <td style="width: 35px;">
                                    <input type="checkbox" ng-model="item.selected">
                                </td>
                                <td style="width: 40%;">
                                    <a ng-href="#/item/{{item.type}}/{{item.name | base64Encode}}">
                                        <span class="label" ng-class="{'label-danger':item.type == 1,'label-success':item.type == 2}"
                                              ng-bind="item.name"></span>
                                    </a>
                                </td>
                                <td ng-bind="item.description"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-success box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('rbac-admin', 'Avaliable') ?>:</h3>
                <div class="box-tools pull-right">
                    <div class="has-feedback">
                        <input type="text" class="form-control input-sm"
                               ng-model="filter.avaliables" placeholder="Search..."
                               ng-change="applyFilter('avaliables')">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                </div>
            </div>
            <div class="box-body no-padding">
                <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button class="btn btn-default btn-sm" ng-click="clickAll('avaliables')">
                        <i class="fa" ng-class="{'fa-check':checkAll.avaliables,'fa-square-o':!checkAll.avaliables}"></i>
                    </button>
                    <button class="btn btn-default btn-sm" ng-click="clickAssign()">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    <div class="pull-right">
                        Page {{pagination.avaliables.page + 1}} of {{pagination.avaliables.total}}
                        <div class="btn-group">
                            <button class="btn btn-default btn-sm" ng-click="clickPrev('avaliables');"
                                    ng-class="{disabled:pagination.avaliables.page <= 0}">
                                <i class="fa fa-chevron-left"></i></button>
                            <button class="btn btn-default btn-sm" ng-click="clickNext('avaliables');"
                                    ng-class="{disabled:pagination.avaliables.page >= pagination.avaliables.total - 1}">
                                <i class="fa fa-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
                <div table-responsive mailbox-messages>
                    <table class="table table-hover">
                        <tbody>
                            <tr ng-repeat="item in displayed.avaliables">
                                <td style="width: 35px;">
                                    <input type="checkbox" ng-model="item.selected">
                                </td>
                                <td style="width: 40%;">
                                    <a ng-href="#/item/{{item.type}}/{{item.name | base64Encode}}">
                                        <span class="label" ng-class="{'label-danger':item.type == 1,'label-success':item.type == 2}"
                                              ng-bind="item.name"></span>
                                    </a>
                                </td>
                                <td ng-bind="item.description"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>