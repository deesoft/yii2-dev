$location = $injector.get('$location');

$scope.model = new Product();
$scope.save = save;
$scope.discard = discard;

function save() {
    var post = {};

    post.code = $scope.model.code;
    post.name = $scope.model.name;
    post.group_id = $scope.model.group_id;
    post.category_id = $scope.model.category_id;

    $scope.model.$save().then(function (model) {
        $location.path('/product/' + model.id);
    }, function (r) {
        $scope.errors = {};
        if (r.status == 422) {
            angular.forEach(r.data, function (v) {
                $scope.errors[v.field] = v.message;
            });
        } else {

        }
    });
}

function discard() {
    window.history.back();
}
