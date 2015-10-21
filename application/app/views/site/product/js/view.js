// model
$scope.model = model;
$scope.deleteModel = deleteModel;

function deleteModel() {
    if (confirm('Are you sure you want to delete')) {
        Product.remove($scope.model.id).then(function () {
            window.history.back();
        });
    }
}
