
var $location = $injector.get('$location');
var $routeParams = $injector.get('$routeParams');
var Rest = $injector.get('Rest');
var myFunc = $injector.get('MovementHelper');

// model
var reffType = undefined, reffId = undefined;

if ($routeParams.reff && $routeParams.id) {
    reffType = $routeParams.reff;
    reffId = $routeParams.id;
    
    Rest(reffType + '/:id').get({
        id: reffId,
        expand: 'branch'
    }, function (row) {
        var model = {};
        if(reffType == 'receive'){
            model.branch_id = row.branch_dest_id;
            model.branch = row.branchDest;
        }else{
            model.branch_id = row.branch_id;
            model.branch = row.branch;
        }
        $scope.model = model;
    });
    Rest(reffType + '/:id/items').query({
        id: reffId,
        expand: 'product,uom,avaliable'
    }, function (rows) {
        for (var i in rows) {
            if(reffType == 'receive'){
                
            }
            rows[i].qty = rows[i].avaliable;
        }
        $scope.items = rows;
        $scope.freeInputDetail = false;
    });
} else {
    $scope.freeInputDetail = true;
    $scope.items = [];
    $scope.model = {};
}
$scope.useReff = reffType != undefined;

// save Item
$scope.save = function () {
    var post = {};
    post.date = $scope.model.date;
    post.warehouse_id = $scope.model.warehouse_id;
    
    post.items = $scope.items;

    Movement.save({}, post, function (model) {
        id = model.id;
        $location.path('/movement/view/' + id);
    }, function (r) {
        $scope.errors = {status: r.status, text: r.statusText, data: {}};
        if (r.status == 422) {
            for (var key in r.data) {
                $scope.errors.data[r.data[key].field] = r.data[key].message;
            }
        }
    });
}

$scope.discard = function () {
    window.history.back();
}