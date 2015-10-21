var $location = $injector.get('$location');
var cast = $injector.get('CastFieldToStr');

if(model.group_id){
    model.group_id = model.group_id.toString();
}
if(model.category_id){
    model.category_id = model.category_id.toString();
}

$scope.model = model;
$scope.save = save;
$scope.discard = discard;

function save() {
//    var post = {};
//
//    post.code = $scope.model.code;
//    post.name = $scope.model.name;
//    post.group_id = $scope.model.group_id;
//    post.category_id = $scope.model.category_id;

    $scope.model.$update().then(function (model) {
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