var $location = $injector.get('$location');

$scope.search = $location.search();
$scope.rows = data.data;
$scope.pagination = data._meta;
$scope.setSearch = setSearch;

function setSearch(field, value) {
    if (value == '') {
        value = undefined;
    }
    $location.search(field, value);
}

function deleteModel(model) {
    if (confirm('Are you sure you want to delete')) {
        Product.remove(model.id).then(function () {
            $route.reload();
        });
    }
}
