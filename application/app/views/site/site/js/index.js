var $q = $injector.get('$q');
var $timeout = $injector.get('$timeout');

function coba(val, t) {
    var defered = $q.defer();
    setTimeout(function () {
        defered.resolve(val);
    }, t ? t : 2000);
    return defered.promise;
}

$scope.klikTest = function () {
    var url = $scope.url;
    var params = {};
    angular.forEach($scope.params.split(','), function (v) {
        v = v.split('=');
        params[v[0]] = v[1];
    });
    $scope.hasil = urlReplace(url, params);
    $scope.extract = extractUrlParams(url);
}

function urlReplace(url, params) {
    return url.replace(/(\/)?:(\w+)/g, function (_, slash, key) {
        if (params[key] === undefined || params[key] === null) {
            return '';
        } else {
            return '' + (slash || '') + encodeURIComponent(params[key]);
        }
    });
}

function extractUrlParams(url) {
    return url.match(/:(\w+)/g)
}