dApp.directive('inputProduct', function () {
    return {
        restrict: 'A',
        scope: {
            func: '=inputProduct',
        },
        link: function (scope, element) {
            element.keypress(function (e) {
                if (e.keyCode == 13) {
                    var code = element.val();
                    var product = yii.app.getProductByCode(code);
                    if (product) {
                        console.log(product.name);
                        scope.func(product);
                        element.val('');
                    }
                }
            });
        }
    };
});

dApp.directive('chgFokus', function () {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            scope.$watch(attrs.chgFokus, function (val) {
                if (val >= 0) {
                    setTimeout(function () {
                        $('tr[data-key="' + val + '"] :input[data-field="qty"]').focus().select();
                        scope[scope.chgFokus] = -1;
                    }, 0);
                }
            });
            element.on('keypress', ':input[data-field]', function (e) {
                if (e.keyCode == 13) {
                    var $th = $(this);
                    var field = $th.data('field');
                    switch (field) {
                        case 'qty':
                            element.find(':input[data-field="uom"]').focus();
                            break;
                        case 'uom':
                            element.find(':input[data-field="price"]').focus().select();
                            break;
                        default :
                            $('#product').focus();
                    }
                }
            });
        }
    };
});

dApp.config(['$httpProvider', function ($httpProvider) {
        $httpProvider.interceptors.push('authInterceptor');
    }
]);

dApp.factory('authInterceptor', ['$q', '$location', function ($q, $location) {
        return {
            request: function (config) {
                var token = yii.app.getToken();
                if (token) {
                    //HttpBearerAuth
                    config.headers.Authorization = 'Bearer ' + token;
                }
                return config;
            },
            responseError: function (rejection) {
                if (rejection.status == 401) {
                    $location.path('/site/login').replace();
                }
                return $q.reject(rejection);
            }
        };
    }]);

dApp.factory('Purchase', ['Rest', function (Rest) {
        return Rest('purchase/:id', {}, {
            items: {method: 'GET', isArray: true, url: 'purchase/:id/items'}
        });
    }]);

dApp.factory('Movement', ['Rest', function (Rest) {
        return Rest('movement/:id', {}, {
            items: {method: 'GET', isArray: true, url: 'movement/:id/items'}
        });
    }]);

dApp.factory('Sales', ['Rest', function (Rest) {
        return Rest('sales/:id', {}, {
            items: {method: 'GET', isArray: true, url: 'sales/:id/items'}
        });
    }]);

dApp.factory('MovementHelper', function () {
    var avaliableField = {
        purchase: ['qty', 'total_receive'],
    };
    return {
        getQtyAvaliable: function (type, row) {
            fs = avaliableField[type];
            if (fs) {
                return row[fs[0]] - row[fs[1]];
            }
        }
    };
});