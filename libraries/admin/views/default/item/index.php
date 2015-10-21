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
                            <td><a ng-href="#/item/{{model.type}}/{{model.name | base64Encode}}">
                                    <span ng-class="{'label-danger':model.type==1,'label-success':model.type==2}"
                                          class="label" ng-bind="model.name"></span></a>
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