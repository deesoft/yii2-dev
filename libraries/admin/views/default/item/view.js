var PAGE_SIZE = 15;

$scope.alerts = [];
var source = {};
var filtered = {
    children: [],
    avaliables: [],
};

var pagination = {
    children: {
        page: 0,
    },
    avaliables: {
        page: 0,
    }
};

$scope.filter = {
    children: '',
    avaliables: '',
};
$scope.applyFilter = applyFilter;
$scope.clickNext = clickNext;
$scope.clickPrev = clickPrev;
$scope.clickAll = clickAll;
$scope.clickAssign = clickAssign;
$scope.clickRevoke = clickRevoke;

$scope.pagination = pagination;
$scope.displayed = {};
$scope.checkAll = {
    children: false,
    avaliables: false,
}

refresh(model);

// definitions
function refresh(model) {
    $scope.model = model;
    source.children = model.children;
    source.avaliables = model.avaliables;

    applyFilter('children');
    applyFilter('avaliables');
}

function applyFilter(f) {
    filtered[f] = $filter('filter')(source[f], $scope.filter[f]);
    
    pagination[f].total = Math.ceil(filtered[f].length / PAGE_SIZE);
    if (pagination[f].page >= pagination[f].total) {
        pagination[f].page = pagination[f].total - 1;
    }
    $scope.displayed[f] = $filter('limitTo')(filtered[f], PAGE_SIZE, pagination[f].page * PAGE_SIZE);
}

function clickNext(f) {
    if (pagination[f].page < pagination[f].total - 1) {
        pagination[f].page++;
    }
    $scope.displayed[f] = $filter('limitTo')(filtered[f], PAGE_SIZE, pagination[f].page * PAGE_SIZE);
}

function clickPrev(f) {
    if (pagination[f].page > 0) {
        pagination[f].page--;
    }
    $scope.displayed[f] = $filter('limitTo')(filtered[f], PAGE_SIZE, pagination[f].page * PAGE_SIZE);
}

function clickAll(f) {
    $scope.checkAll[f] = !$scope.checkAll[f];
    angular.forEach($scope.displayed[f], function (item) {
        item.selected = $scope.checkAll[f];
    });
}

function clickRevoke() {
    var items = $filter('filter')($scope.displayed.assignment, {selected: true});
    if (items.length > 0) {
        var post = {
            items: jQuery.map(items, function (item) {
                return item.name;
            }),
        };
        $scope.model.$revoke({id:base64Encode($scope.model.name)}, post).then(function (r) {
            addAlert('info', r.count + ' item(s) revoked');
            $scope.model.get().then(function (m) {
                refresh(m);
            });
        }, function (r) {
            addAlert('error', r.statusText);
        });
    }
}

function clickAssign() {
    var items = $filter('filter')($scope.displayed.assignment, {selected: true});
    if (items.length > 0) {
        var post = {
            items: jQuery.map(items, function (item) {
                return item.name;
            }),
        };
        $scope.model.$assign({id:base64Encode($scope.model.name)}, post).then(function (r) {
            addAlert('info', r.count + ' item(s) revoked');
            $scope.model.get().then(function (m) {
                refresh(m);
            });
        }, function (r) {
            addAlert('error', r.statusText);
        });
    }
}
