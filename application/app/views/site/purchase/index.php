<?php

use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

$this->registerCss(<<<CSS
[d-sort] .asc{
    color:red;
}
[d-sort] .desc{
    color:yellow;
}

CSS
)
?>

<div class="purchase-index">
    <page title="Purchase"></page>
    <p>
        <?= Html::a('Create', '#/purchase/new', ['class' => 'btn btn-success']) ?>
    </p>

    <div class="grid-view">
        <table class="table table-striped table-bordered">
            <thead>
                <tr d-sort="search.sort" d-change="setSearch('sort',$value)">
                    <th >#</th>
                    <th><a href sort-field="id" >Id</a></th>
                    <th><a href sort-field="number">Number</a></th>
                    <th><a href sort-field="supplier_id" >Supplier</a></th>
                    <th><a href sort-field="branch_id" >Branch</a></th>
                    <th><a href sort-field="date" >Date</a></th>
                    <th><a href sort-field="value">Value</a></th>
                    <th><a href sort-field="discount">Discount</a></th>
                    <th><a href sort-field="status">Status</a></th>
                    <!--
                        <th><a href sort-field="created_at">Created_at</a></th>
                        <th><a href sort-field="created_by">Created_by</a></th>
                        <th><a href sort-field="updated_at">Updated_at</a></th>
                        <th><a href sort-field="updated_by">Updated_by</a></th>
                    -->
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="model in rows">
                    <td>{{(pagination.currentPage - 1) * pagination.perPage + $index + 1}}</td>
                    <td>{{model.id}}</td>
                    <td>{{model.number}}</td>
                    <td>{{model.supplier.name}}</td>
                    <td>{{model.branch.name}}</td>
                    <td>{{model.date | date:'dd-MM-yyyy'}}</td>
                    <td>{{model.value}}</td>
                    <td>{{model.discount}}</td>
                    <td>{{model.nmStatus}}</td>
                    <!--
                        <td>{{model.created_at}}</td>
                        <td>{{model.created_by}}</td>
                        <td>{{model.updated_at}}</td>
                        <td>{{model.updated_by}}</td>
                    -->
                    <td>
                        <a ng-href="#/purchase/{{model.id}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                        <a ng-href="#/purchase/{{model.id}}/edit" ng-if="model.status == 10"><span class="glyphicon glyphicon-pencil"></span></a>
                        <a href ng-click="deleteModel(model)" ng-if="model.status == 10"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                </tr>
            </tbody>
        </table>
        <pagination total-items="pagination.totalCount" ng-model="pagination.currentPage"
                    max-size="5" items-per-page="pagination.perPage"
                    ng-change="setSearch('page',pagination.currentPage)"
                    class="pagination-sm" boundary-links="true"></pagination>
    </div>
</div>
