<?php

use dee\angular\NgView;

/* @var $this yii\web\View */
/* @var $widget NgView */
?>
<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('rbac-admin', 'Rules')?></h3>
    </div>
    <div class="box-body">
        <alert ng-repeat="alert in alerts" type="{{alert.type}}" close="closeAlert($index)"
               dismiss-on-timeout="{{alert.timeout}}">{{alert.msg}}</alert>
        <table class="table">
            <thead>
                <tr>
                    <td width="30px"></td>
                    <td width="300px">
                        <div class="has-feedback">
                            <input type="text" class="form-control input-sm" placeholder="Search" ng-model="q"
                                   ng-change="filter()">
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
                    <tr ng-repeat="model in filtered|limitTo:perPage:(currentPage-1)*perPage">
                        <td width="35px">{{(currentPage-1)*perPage + $index + 1}}</td>
                        <td>{{model.name}}</td>
                        <td><a href ng-click="showItem(model)">{{model.className}}</a></td>
                        <td width="60px">
                            <a href ng-click="deleteItem(model)" ng-if="model.name != 'route_rule'"><span class="glyphicon glyphicon-trash"></span></a>
                            <a href ng-click="editItem(model)"><span class="glyphicon glyphicon-pencil"></span></a>
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