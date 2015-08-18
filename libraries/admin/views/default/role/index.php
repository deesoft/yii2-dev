<?php

use dee\angular\Angular;

/* @var $this yii\web\View */
/* @var $angular Angular */

$angular->renderJs('js/index.js');
?>
<div class="box box-success box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Roles</h3>
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
                                <input type="text" class="form-control input-sm" placeholder="Search">
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
            <table class="table table-striped">
                <tbody>
                    <tr ng-repeat="model in filtered.slice(provider.offset, provider.offset + provider.itemPerPage)">
                        <td width="35px">{{provider.offset + $index + 1}}</td>
                        <td>{{model.name}}</td>
                        <td>{{model.description}}</td>
                        <td width="40px">
                            <a ng-href="#/role/{{model.name}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <pagination total-items="filtered.length" ng-model="provider.page"
                        max-size="3" items-per-page="provider.itemPerPage"
                        ng-change="provider.paging()" direction-links="false"
                        first-text="&laquo;" last-text="&raquo;"
                        class="pagination-sm" boundary-links="true"></pagination>
        </div>
    </div>
</div>