<?php

use dee\angular\Angular;

/* @var $this yii\web\View */
/* @var $angular Angular */

$angular->renderJs('js/index.js');
?>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Assignment</h3>
    </div>
    <div class="box-body">
        <div class="grid-view">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <td></td>
                        <td colspan="3">
                            <div class="has-feedback col-md-5">
                                <input type="text" class="form-control input-sm" placeholder="Search">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </td>
                    </tr>
                    <tr >
                        <th width="30px">#</th>
                        <th><a href >Name</a></th>
                        <th><a href >Assignments</a></th>
                        <th width="40px"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="model in rows">
                        <td>{{(provider.page - 1) * provider.itemPerPage + $index + 1}}</td>
                        <td>{{model.username}}</td>
                        <td><a ng-repeat="role in model.assignments" ng-href="#role/{{role.name}}">
                                <span class="label" ng-class="{'label-info':role.type == 1,'label-warning':role.type == 2}">{{role.name}}</span></a>
                        </td>
                        <td>
                            <a ng-href="#/assignment/{{model.id}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <pagination total-items="provider.totalItems" ng-model="provider.page"
                        max-size="5" items-per-page="provider.itemPerPage"
                        ng-change="provider.paging()"
                        class="pagination-sm" boundary-links="true"></pagination>
        </div>
    </div>
</div>