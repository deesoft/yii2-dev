var $location = $injector.get('$location');
var search = $location.search();

query = function () {
    Sales.query({
        page: search.page,
        sort: search.sort,
        expand: 'customer,branch',
    }, function (rows, headerCallback) {
        yii.angular.getPageInfo($scope.provider, headerCallback);
        $scope.rows = rows;
    });
}

// data provider
$scope.provider = {
    sort: search.sort,
    paging: function () {
        search.page = $scope.provider.page;
        $location.search(search);
    },
    sorting: function () {
        search.sort = $scope.provider.sort;
        $location.search(search);
    }
};

// delete Item
$scope.deleteModel = function (model) {
    if (confirm('Are you sure you want to delete')) {
        id = model.id;
        Sales.remove({id: id}, {}, function () {
            query();
        });
    }
}

query();