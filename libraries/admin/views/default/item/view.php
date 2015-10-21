<?php

use dee\angular\NgView;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('rbac-admin', 'Role')?></h3>
                <div class="box-tools pull-right">
                    <button ng-if="!isEdit" class="btn btn-box-tool" ng-click="clickEdit()"><span class="fa fa-pencil"></span></button>
                    <button ng-if="!isEdit" class="btn btn-box-tool" ng-click="clickDelete()"><span class="fa fa-trash"></span></button>
                    <button ng-if="!!isEdit" class="btn btn-box-tool" ng-click="clickSave()"><span class="fa fa-save"></span></button>
                    <button ng-if="!!isEdit" class="btn btn-box-tool" ng-click="clickCancel()"><span class="fa fa-times-circle-o"></span></button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="box-body form-horizontal">
                        <div class="form-group" ng-class="{'has-error':modelError.name}">
                            <label class="col-sm-3 control-label"><?= Yii::t('rbac-admin', 'Name')?></label>
                            <div class="col-sm-9">
                                <input ng-if="isEdit" class="form-control" ng-model="edit.name">
                                <div ng-if="isEdit && modelError.name" class="help-block" ng-bind="modelError.name"></div>
                                <p ng-if="!isEdit" class="form-control-static" ng-bind="model.name"></p>
                            </div>
                        </div>
                        <div class="form-group" ng-class="{'has-error':modelError.description}">
                            <label class="col-sm-3 control-label"><?= Yii::t('rbac-admin', 'Description')?></label>
                            <div class="col-sm-9">
                                <input ng-if="isEdit" class="form-control" ng-model="edit.description">
                                <div ng-if="isEdit && modelError.name" class="help-block" ng-bind="modelError.description"></div>
                                <p ng-if="!isEdit" class="form-control-static" ng-bind="model.description"></p>
                            </div>
                        </div>
                        <div class="form-group" ng-class="{'has-error':modelError.ruleName}">
                            <label class="col-sm-3 control-label"><?= Yii::t('rbac-admin', 'Rule')?></label>
                            <div class="col-sm-9">
                                <select ng-if="isEdit" class="form-control" ng-model="edit.ruleName"
                                        ng-options="rule for rule in rules">
                                    <option></option>
                                </select>
                                <div ng-if="isEdit && modelError.name" class="help-block" ng-bind="modelError.ruleName"></div>
                                <p ng-if="!isEdit" class="form-control-static" ng-bind="model.ruleName"></p>
                            </div>
                        </div>
                        <div class="form-group" ng-class="{'has-error':modelError.data}">
                            <label class="col-sm-3 control-label"><?= Yii::t('rbac-admin', 'Data')?></label>
                            <div class="col-sm-9">
                                <textarea ng-if="isEdit" class="form-control" ng-model="edit.data"></textarea>
                                <div ng-if="isEdit && modelError.name" class="help-block" ng-bind="modelError.data"></div>
                                <p ng-if="!isEdit" class="form-control-static" ng-bind="model.data"></p>
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
                <h3 class="box-title"><?= Yii::t('rbac-admin', 'Children')?>:</h3>
                <div class="box-tools pull-right">
                    <div class="has-feedback">
                        <input type="text" class="form-control input-sm"
                               ng-model="filter.children" placeholder="Search..."
                               ng-change="applyFilter('children')">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                </div>
            </div>
            <div class="box-body no-padding">
                <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button class="btn btn-default btn-sm" ng-click="clickAll('children')">
                        <i class="fa" ng-class="{'fa-check':checkAll.children,'fa-square-o':!checkAll.children}"></i>
                    </button>
                    <button class="btn btn-default btn-sm" ng-click="clickRevoke()">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                    <div class="pull-right">
                        {{pagination.children.page+1}} of {{pagination.children.total}}
                        <div class="btn-group">
                            <button class="btn btn-default btn-sm" ng-click="clickPrev('children')"
                                    ng-class="{disabled:pagination.children.page <= 0}">
                                <i class="fa fa-chevron-left"></i></button>
                            <button class="btn btn-default btn-sm" ng-click="clickNext('children')"
                                    ng-class="{disabled:pagination.children.page+1 >= pagination.children.total}">
                                <i class="fa fa-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
                <div table-responsive mailbox-messages>
                    <table class="table table-hover">
                        <tbody>
                            <tr ng-repeat="item in displayed.children">
                                <td style="width: 35px;">
                                    <input type="checkbox" ng-model="item.selected">
                                </td>
                                <td ng-if="item.name.charAt(0) != '/'" style="width: 40%;">
                                    <span class="label" ng-class="{'label-danger':item.type == 1,'label-success':item.type == 2}">{{item.name}}</span>
                                </td>
                                <td ng-if="item.name.charAt(0) == '/'" colspan="2"><span class="label label-default">{{item.name}}</span></td>
                                <td ng-if="item.name.charAt(0) != '/'">{{item.description}}</td>
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
                <h3 class="box-title"><?= Yii::t('rbac-admin', 'Avaliable')?>:</h3>
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
                    <button class="btn btn-default btn-sm" ng-click="clickRevoke()">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                    <div class="pull-right">
                        {{pagination.avaliables.page+1}} of {{pagination.avaliables.total}}
                        <div class="btn-group">
                            <button class="btn btn-default btn-sm" ng-click="clickPrev('avaliables')"
                                    ng-class="{disabled:pagination.avaliables.page <= 0}">
                                <i class="fa fa-chevron-left"></i></button>
                            <button class="btn btn-default btn-sm" ng-click="clickNext('avaliables')"
                                    ng-class="{disabled:pagination.avaliables.page+1 >= pagination.avaliables.total}">
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
                                <td ng-if="item.name.charAt(0) != '/'" style="width: 40%;">
                                    <span class="label" ng-class="{'label-danger':item.type == 1,'label-success':item.type == 2}">{{item.name}}</span>
                                </td>
                                <td ng-if="item.name.charAt(0) == '/'" colspan="2"><span class="label label-default">{{item.name}}</span></td>
                                <td ng-if="item.name.charAt(0) != '/'">{{item.description}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>