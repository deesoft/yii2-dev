var $location = $injector.get('$location');
var search = $location.search();

query = function () {
    Assignment.query({
        page: search.page,
        sort: search.sort,
        q:search.q,
        expand: 'assignments',
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

query();