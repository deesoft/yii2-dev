var PAGE_SIZE = 15;

$scope.alerts = [];
var source = {};
var filtered = {};

var pagination = {
    assignments: {
        page: 0,
    },
    avaliables: {
        page: 0,
    }
};

$scope.filter = {
    assignments: '',
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
    assignments: false,
    avaliables: false,
}

refresh(model);

// definitions
function refresh(model) {
    $scope.model = model;
    source.assignments = model.assignments;
    source.avaliables = model.avaliables;
    
    applyFilter('assignments',$scope.filter.assignments);
    applyFilter('avaliables',$scope.filter.avaliables);
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
        $scope.model.revoke({}, post).then(function (r) {
            addAlert('info', r.count + ' item(s) revoked');
            $scope.model.get().then(function(m){
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
        $scope.model.assign({}, post).then(function (r) {
            addAlert('info', r.count + ' item(s) revoked');
            $scope.model.get().then(function(m){
                refresh(m);
            });
        }, function (r) {
            addAlert('error', r.statusText);
        });
    }
}


$scope.modelError = {};

$scope.page1 = {
    pageSize: 15,
    total: 0,
    totalPage: 0,
    page: 0,
    begin: 0,
    end: 0,
    next: function () {
        if (page1.page < page1.totalPage - 1) {
            page1.page++;
            page1.begin = page1.page * page1.pageSize;
            page1.end = Math.min(page1.begin + page1.pageSize, page1.total);
            $scope.displayed1 = $scope.filtered1.slice(page1.begin, page1.end);
        }
    },
    prev: function () {
        if (page1.page > 0) {
            page1.page--;
            page1.begin = page1.page * page1.pageSize;
            page1.end = Math.min(page1.begin + page1.pageSize, page1.total);
            $scope.displayed1 = $scope.filtered1.slice(page1.begin, page1.end);
        }
    }
};
$scope.page2 = {
    pageSize: 15,
    total: 0,
    totalPage: 0,
    page: 0,
    begin: 0,
    end: 0,
    next: function () {
        if (page2.page < page2.totalPage - 1) {
            page2.page++;
            page2.begin = page2.page * page2.pageSize;
            page2.end = Math.min(page2.begin + page2.pageSize, page2.total);
            $scope.displayed2 = $scope.filtered2.slice(page2.begin, page2.end);
        }
    },
    prev: function () {
        if (page2.page > 0) {
            page2.page--;
            page2.begin = page2.page * page2.pageSize;
            page2.end = Math.min(page2.begin + page2.pageSize, page2.total);
            $scope.displayed2 = $scope.filtered2.slice(page2.begin, page2.end);
        }
    }
};

page1 = $scope.page1;
page2 = $scope.page2;

query = function () {
    Item.get({
        id: $scope.paramId,
        expand: 'children,avaliables,rules'
    }, function (row) {
        $scope.model = row;

        $scope.children = row.children;
        $scope.avaliables = row.avaliables;
        $scope.rules = row.rules;

        $scope.applyFilter1();
        $scope.applyFilter2();
    }, function (r) {
        if (r.status = 404) {
            $location.path('/error/404');
        }else{
            window.alert(r.statusText);
            window.history.back();
        }
    });
};
query();

$scope.applyFilter1 = function () {
    $scope.filtered1 = $filter('filter')($scope.children, $scope.q1);
    page1.total = $scope.filtered1.length;
    page1.totalPage = Math.ceil(page1.total / page1.pageSize);
    page1.page = Math.max(Math.min(page1.page, page1.totalPage - 1), 0);
    page1.begin = page1.page * page1.pageSize;
    page1.end = Math.min(page1.begin + page1.pageSize, page1.total);

    $scope.displayed1 = $scope.filtered1.slice(page1.begin, page1.end);
}

$scope.applyFilter2 = function () {
    $scope.filtered2 = $filter('filter')($scope.avaliables, $scope.q2);
    page2.total = $scope.filtered2.length;
    page2.totalPage = Math.ceil(page2.total / page2.pageSize);
    page2.page = Math.max(Math.min(page2.page, page2.totalPage - 1), 0);
    page2.begin = page2.page * page2.pageSize;
    page2.end = Math.min(page2.begin + page2.pageSize, page2.total);

    $scope.displayed2 = $scope.filtered2.slice(page2.begin, page2.end);
}

$scope.changeAll1 = function () {
    $scope.all1 = !$scope.all1;
    angular.forEach($scope.displayed1, function (item) {
        item.selected = $scope.all1;
    });
}

$scope.changeAll2 = function () {
    $scope.all2 = !$scope.all2;
    angular.forEach($scope.displayed2, function (item) {
        item.selected = $scope.all2;
    });
}

$scope.clickRevoke = function () {
    var items = $filter('filter')($scope.displayed1, {selected: true});
    if (items.length > 0) {
        var post = {
            items: jQuery.map(items, function (item) {
                return item.name;
            }),
        };
        Item.revoke({id: $scope.paramId}, post,
            function (r) {
                addAlert('info', r.count + ' item(s) revoked');
                query();
            },
            function (r) {
                addAlert('error', r.statusText);
            });
    }
}

$scope.clickAssign = function () {
    var items = $filter('filter')($scope.displayed2, {selected: true});
    if (items.length > 0) {
        var post = {
            items: jQuery.map(items, function (item) {
                return item.name;
            }),
        };
        Item.assign({id: $scope.paramId}, post,
            function (r) {
                addAlert('info', r.count + ' item(s) assigned');
                query();
            },
            function (r) {
                addAlert('error', r.statusText);
            });
    }
}


$scope.clickEdit = function () {
    $scope.isEdit = true;
    $scope.edit = {
        name: $scope.model.name,
        description: $scope.model.description,
        ruleName: $scope.model.ruleName,
        data: $scope.model.data,
    };
}

$scope.clickDelete = function () {
    if (confirm('Are you sure you want to delete?')) {
        Item.remove({id: $scope.paramId}, {}, function () {
            $location.path('/role');
        }, function (r) {
            addAlert('error', r.statusText);
        });
    }
}

$scope.clickSave = function () {
    Item.update({id: $scope.paramId}, $scope.edit,
        function (r) {
            if (r.name != $scope.paramId) {
                $location.path('/role/' + r.name);
            } else {
                $scope.model = r;
            }
            $scope.isEdit = false;
            $scope.modelError = {};
            addAlert('info', 'Save success');
        }, function (r) {
        if (r.status == 422) {
            angular.forEach(r.data, function (err) {
                $scope.modelError[err.field] = err.message;
            });
        } else {
            addAlert('error', r.statusText);
        }
    });
}

$scope.clickCancel = function () {
    $scope.isEdit = false;
    $scope.modelError = {};
}

addAlert = function (type, msg) {
    var alert = {type: type, msg: msg};
    if (type == 'info') {
        alert.time = 3000;
    }
    $scope.alerts.push(alert);
};

$scope.closeAlert = function (index) {
    $scope.alerts.splice(index, 1);
};