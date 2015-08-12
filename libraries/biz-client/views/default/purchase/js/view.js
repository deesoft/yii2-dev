
var $location = $injector.get('$location');
var $routeParams = $injector.get('$routeParams');
var $route = $injector.get('$route');

$scope.paramId = $routeParams.id;
// model
Purchase.get({
    id: $scope.paramId, 
    expand: 'supplier,branch,items.product,items.uom'
}, function (row) {
    $scope.model = row;
});

// delete Item
$scope.deleteModel = function(){
    if(confirm('Are you sure you want to delete')){
        Purchase.remove({id:$scope.paramId},{},function(){
            $location.path('/purchase/');
        });
    }
}

// confirm
$scope.confirm = function(){
    if(confirm('Are you sure you want to save')){
        Purchase.patch({id:$scope.paramId},[
            {field:'status',value:20}
        ],function(){
            $route.reload();
        });
    }
}

// confirm
$scope.reject = function(){
    if(confirm('Are you sure you want to reject status')){
        Purchase.patch({id:$scope.paramId},[
            {field:'status',value:10}
        ],function(){
            $route.reload();
        });
    }
}

