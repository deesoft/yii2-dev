var rows;
var rules;

$scope.perPage = 20;
$scope.page = 1;
$scope.q = '';
$scope.alerts = [];
$scope.currentPage = 1;
$scope.doFilter = doFilter;
$scope.openModal = openModal;
$scope.deleteItem = deleteItem;
$scope.closeAlert = closeAlert;

doFocus('q');
refresh(data.data);

// definition
function refresh(data) {
    rows = data;
    doFilter();
}

function doFilter() {
    $scope.filtered = $filter('filter')(rows, $scope.q);
}

function openModal() {
    $modal.open(angular.merge({}, widget.templates['/item/form'], {
        animation: true,
        resolve: {
            type: function () {
                return 1;
            },
            rules:['Rule',function(Rule){
                    if(rules){
                        return rules;
                    }else{
                        return Rule.query().then(function (r){
                            rules = r;
                            return rules;
                        });
                    }                    
            }],
        }
    })).result.then(function () {
        addAlert('info', 'New role added');
        Item.query({type: 1}).then(function (r) {
            refresh(r.data);
        });
    });
}

function deleteItem(item) {
    if (confirm('Are you sure you want to delete?')) {
        Item.remove({id: item.name}).then(function () {
            addAlert('info', 'Role deleted');
            Item.query({type: 1}).then(function (r) {
                refresh(r.data);
            });
        }, function (r) {
            addAlert('error', r.statusText);
        });
    }
}

addAlert = function (type, msg) {
    var alert = {type: type, msg: msg};
    if (type == 'info') {
        alert.timeout = 3000;
    }
    $scope.alerts.push(alert);
};

function closeAlert(index) {
    $scope.alerts.splice(index, 1);
}
;