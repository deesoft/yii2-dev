
$location = $injector.get('$location');
$routeParams = $injector.get('$routeParams');
var $route = $injector.get('$route');

$scope.paramId = $routeParams.id;
// model
Movement.get({id: $scope.paramId, 
    expand: 'warehouse,branch,items.product,items.uom'
}, function (row) {
    $scope.model = row;
});

// delete Item
$scope.deleteModel = function(){
    if(confirm('Are you sure you want to delete')){
        Movement.remove({id:$scope.paramId},{},function(){
            $location.path('/movement/');
        });
    }
}

// confirm
$scope.apply = function(){
    if(confirm('Are you sure you want to save')){
        Movement.patch({id:$scope.paramId},[
            {field:'status',value:20}
        ],function(){
            $route.reload();
        });
    }
}

// confirm
$scope.reject = function(){
    if(confirm('Are you sure you want to reject status')){
        Movement.patch({id:$scope.paramId},[
            {field:'status',value:10}
        ],function(){
            $route.reload();
        });
    }
}
