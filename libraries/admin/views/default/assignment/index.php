<?php

use dee\angular\NgView;

/* @var $this yii\web\View */
/* @var $widget NgView */
?>
<div class="box box-solid box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('rbac-admin', 'Assignment')?></h3>
    </div>
    <div class="box-body">
        <table class="table">
            <thead>
                <tr>
                    <td width="35px"></td>
                    <td width="300px">
                        <div class="has-feedback">
                            <input type="text" class="form-control input-sm" placeholder="Search" ng-model="search.q"
                                   ng-change="setSearch('q',search.q)" focus-me="q" ng-model-options="{updateOn:'blur change'}">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </thead>
        </table>
        <div class="grid-view">
            <table class="table table-striped table-bordered">
                <tbody>
                    <tr ng-repeat="model in rows">
                        <td width="35px;">{{(pagination.currentPage - 1) * pagination.perPage + $index + 1}}</td>
                        <td><a ng-href="#/assignment/{{model.id}}" ng-bind="model.username"></a></td>
                        <td><a ng-repeat="role in model.assignments" ng-href="#/item/{{role.type}}/{{role.name | base64Encode}}">
                                <span class="label" ng-class="{'label-danger':role.type == 1,'label-success':role.type == 2}">{{role.name}}</span></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <pagination total-items="pagination.totalCount" ng-model="pagination.currentPage"
                        max-size="3" items-per-page="pagination.perPage"
                        ng-change="setSearch('page',pagination.currentPage)" direction-links="false"
                        first-text="&laquo;" last-text="&raquo;"
                        class="pagination-sm" boundary-links="true"></pagination>
        </div>
    </div>
</div>