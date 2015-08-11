$scope.errors = {data:{}};

jQuery('form').on('keypress change',':input[ng-model]',function(){
    var field = $(this).attr('name');
    if($scope.errors.status){
        delete $scope.errors.status;
        delete $scope.errors.text;
    }
    if($scope.errors.data[field]){
        delete $scope.errors.data[field];
    }
});