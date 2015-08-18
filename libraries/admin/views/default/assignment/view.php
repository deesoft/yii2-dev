<?php

use dee\angular\Angular;

/* @var $this yii\web\View */
/* @var $angular Angular */

$angular->renderJs('js/view.js');
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">User: {{model.username}}</h3>
    </div>
    <div class="box-body">
        <div class="col-xs-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Assigned:</h3>
                    <div class="box-tools pull-right">
                        <div class="has-feedback">
                            <input type="text" class="form-control input-sm" ng-model="q1"placeholder="Search...">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <div class="mailbox-controls">
                        <!-- Check all button -->
                        <button class="btn btn-default btn-sm" btn-checkbox ng-model="all1" ng-change="changeAll1()">
                            <i class="fa" ng-class="{'fa-check':all1,'fa-square-o':!all1}"></i>
                        </button>
                        <button class="btn btn-default btn-sm" ng-click="clickRevoke()">
                            <i class="fa fa-arrow-right"></i></button>
                    </div>
                    <div table-responsive mailbox-messages>
                        <table class="table table-hover">
                            <tbody>
                                <tr ng-if="roles1.length > 0">
                                    <th></th>
                                    <th colspan="2">Roles:</th>
                                </tr>
                                <tr ng-repeat="item in assignments| filter:{type:1,'$':q1} as roles1">
                                    <td style="width: 35px;"><input type="checkbox" ng-model="item.selected"></td>
                                    <td class="mailbox-name">{{item.name}}</td>
                                    <td class="mailbox-messages">{{item.description}}</td>
                                </tr>
                                <tr ng-if="permissions1.length > 0">
                                    <th></th>
                                    <th colspan="2">Permissions:</th>
                                </tr>
                                <tr ng-repeat="item in assignments| filter:{type:2,'$':q1} as permissions1">
                                    <td style="width: 35px;"><input type="checkbox" ng-model="item.selected"></td>
                                    <td class="mailbox-name">{{item.name}}</td>
                                    <td class="mailbox-messages">{{item.description}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Avaliable:</h3>
                    <div class="box-tools pull-right">
                        <div class="has-feedback">
                            <input type="text" class="form-control input-sm" ng-model="q2"placeholder="Search...">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <div class="mailbox-controls">
                        <!-- Check all button -->
                        <button class="btn btn-default btn-sm" btn-checkbox ng-model="all2" ng-change="changeAll2()">
                            <i class="fa" ng-class="{'fa-check':all2,'fa-square-o':!all2}"></i>
                        </button>
                        <button class="btn btn-default btn-sm" ng-click="clickAssign()">
                            <i class="fa fa-arrow-left"></i></button>
                    </div>

                    <div table-responsive mailbox-messages>
                        <table class="table table-hover">
                            <tbody>
                                <tr ng-if="roles2.length > 0">
                                    <th></th>
                                    <th colspan="2">Roles:</th>
                                </tr>
                                <tr ng-repeat="item in avaliables| filter:{type:1,'$':q2} as roles2">
                                    <td style="width: 35px;"><input type="checkbox" ng-model="item.selected"></td>
                                    <td class="mailbox-name">{{item.name}}</td>
                                    <td class="mailbox-messages">{{item.description}}</td>
                                </tr>
                                <tr ng-if="permissions2.length > 0">
                                    <th></th>
                                    <th colspan="2">Permissions:</th>
                                </tr>
                                <tr ng-repeat="item in avaliables| filter:{type:2,'$':q2} as permissions2">
                                    <td style="width: 35px;"><input type="checkbox" ng-model="item.selected"></td>
                                    <td class="mailbox-name">{{item.name}}</td>
                                    <td class="mailbox-messages">{{item.description}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>