$scope.ok = function () {
    $modalInstance.close($scope.route);
};

$scope.cancel = function () {
    $modalInstance.dismiss('cancel');
};