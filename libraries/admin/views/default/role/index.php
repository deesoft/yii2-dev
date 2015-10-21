<?php

use dee\angular\NgView;

/* @var $this yii\web\View */
/* @var $widget NgView */
?>
<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('rbac-admin', 'Roles') ?></h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="box-body form-horizontal">
                    <div class="form-group" ng-class="{'has-error':modelError.name}">
                        <label class="col-sm-3 control-label"><?= Yii::t('rbac-admin', 'Name') ?></label>
                        <div class="col-sm-9">
                            <input ng-if="isEdit" class="form-control" ng-model="edit.name">
                            <div ng-if="isEdit && modelError.name" class="help-block" ng-bind="modelError.name"></div>
                            <p ng-if="!isEdit" class="form-control-static" ng-bind="model.name"></p>
                        </div>
                    </div>
                    <div class="form-group" ng-class="{'has-error':modelError.description}">
                        <label class="col-sm-3 control-label"><?= Yii::t('rbac-admin', 'Description') ?></label>
                        <div class="col-sm-9">
                            <input ng-if="isEdit" class="form-control" ng-model="edit.description">
                            <div ng-if="isEdit && modelError.name" class="help-block" ng-bind="modelError.description"></div>
                            <p ng-if="!isEdit" class="form-control-static" ng-bind="model.description"></p>
                        </div>
                    </div>
                    <div class="form-group" ng-class="{'has-error':modelError.ruleName}">
                        <label class="col-sm-3 control-label"><?= Yii::t('rbac-admin', 'Rule') ?></label>
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
                        <label class="col-sm-3 control-label"><?= Yii::t('rbac-admin', 'Data') ?></label>
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
        <div class="row">
            <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <td width="30px"></td>
                        <td width="300px">
                            <div class="has-feedback">
                                <input type="text" class="form-control input-sm" placeholder="Search" ng-model="q"
                                       ng-change="doFilter()" focus-me="q">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" ng-click="openModal()">
                                <span class="fa fa-plus"></span></button>
                        </td>
                    </tr>
                </thead>
            </table>

            <div class="grid-view">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr ng-repeat="model in filtered| limitTo:perPage:(currentPage-1)*perPage">
                            <td width="35px">{{(currentPage - 1) * perPage + $index + 1}}</td>
                            <td><a ng-href="#/role/{{model.name}}">
                                    <span class="label label-danger" ng-bind="model.name"></span></a>
                            </td>
                            <td ng-bind="model.description"></td>
                            <td width="35px">
                                <a href ng-click="deleteItem(model)"><span class="glyphicon glyphicon-trash"></span></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <pagination total-items="filtered.length" ng-model="currentPage"
                            max-size="3" items-per-page="perPage"
                            direction-links="false"
                            first-text="&laquo;" last-text="&raquo;"
                            class="pagination-sm" boundary-links="true"></pagination>
            </div>
            </div>
        </div>
    </div>
</div>