$scope.rules = rules.data;

$scope.model = new Item({
    type:type,
});
$scope.modelError = {};
$scope.closeAlert = closeAlert;
$scope.ok = ok;
$scope.cancel = cancel;

// definitions
function ok(){
    $scope.model.$save().then(function(r){
        $scope.modelError = {};
        $modalInstance.close(r);
    },function(r){
        if (r.status == 422) {
            angular.forEach(r.data,function(err){
                $scope.modelError[err.field] = err.message;
            });
        }else{
            $scope.statusText = r.statusText;
        }
    });
}

function cancel(){
    $modalInstance.dismiss('cancel');
}

function closeAlert(){
    delete $scope.statusText;
}