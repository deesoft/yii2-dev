$scope.name = item.name;
$scope.content = $sce.trustAsHtml(item.content);

$scope.close = function () {
    $modalInstance.dismiss('cancel');
}