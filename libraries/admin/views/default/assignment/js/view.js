
var $location = $injector.get('$location');
var $routeParams = $injector.get('$routeParams');
var $route = $injector.get('$route');
var filterFilter = $injector.get('filterFilter');

$scope.paramId = $routeParams.id;

$scope.assignments = [];
$scope.avaliables = [];
$scope.all1 = false;
$scope.all2 = false;
$scope.q1 = '';
$scope.q2 = '';

query = function () {

    Assignment.get({
        id: $scope.paramId,
        expand: 'assignments,avaliables'
    }, function (row) {
        $scope.model = row;
        $scope.assignments = row.assignments;
        $scope.avaliables = row.avaliables;
    });
};

query();

$scope.changeAll1 = function () {
    angular.forEach($scope.assignments, function (item) {
        item.selected = $scope.all1;
    });
}

$scope.changeAll2 = function () {
    angular.forEach($scope.avaliables, function (item) {
        item.selected = $scope.all2;
    });
}

$scope.clickRevoke = function () {
    var items = filterFilter($scope.assignments, {'$': $scope.q1, selected: true});
    if (items.length > 0) {
        var post = {
            items: jQuery.map(items, function (item) {
                return item.name;
            }),
        };
        Assignment.revoke({id: $scope.paramId}, post,
            function (r) {
                $scope.flashMsg = r.count + ' item(s) revoked';
                query();
            }, function (r) {
            console.log(r.data);
        });
    }
}

$scope.clickAssign = function () {
    var items = filterFilter($scope.avaliables, {'$': $scope.q2, selected: true});
    if (items.length > 0) {
        var post = {
            items: jQuery.map(items, function (item) {
                return item.name;
            }),
        };
        Assignment.assign({id: $scope.paramId}, post,
            function (r) {
                $scope.flashMsg = r.count + ' item(s) assigned';
                query();
            }, function (r) {
            console.log(r.data);
        });
    }
}
