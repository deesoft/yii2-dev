
// data provider
$scope.provider = {
    multisort: false,
    query: function(){
        Sales.query({
            page: $scope.provider.currentPage,
            sort: $scope.provider.sort,
            expand:'supplier,branch',
        }, function (rows, headerCallback) {
            yii.angular.getPagerInfo($scope.provider, headerCallback);
            $scope.rows = rows;
        });
    }
};

// initial load
$scope.provider.query();

// delete Item
$scope.deleteModel = function(model){
    if(confirm('Are you sure you want to delete')){
        id = model.id;
        Sales.remove({id:id},{},function(){
            $scope.provider.query();
        });
    }
}