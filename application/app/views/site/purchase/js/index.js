var $location = $injector.get('$location');
var $route = $injector.get('$route');
$scope.search = $location.search();

$scope.rows = data.data;
$scope.deleteModel = deleteModel;
$scope.pagination = data._meta;
$scope.setSearch = setSearch;

function deleteModel(model) {
    if (confirm('Are you sure you want to delete')) {
        Purchase.remove(model.id)
            .then(function () {
                $route.reload();
            });
    }
}

function setSearch(field, val) {
    $location.search(field, val);
}

