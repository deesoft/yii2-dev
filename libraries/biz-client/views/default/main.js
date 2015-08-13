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
            var fields = angular.isDefined(attrs.fields) ? attrs.fields.split(',') : false;
            if (fields) {
                element.on('keypress', ':input[data-field]', function (e) {
                    if (e.keyCode == 13) {
                        var $th = $(this);
                        var field = $th.data('field');
                        for (var i = 0; i < fields.length; i++) {
                            if (fields[i] == field && fields[i + 1] != undefined) {
                                element.find(':input[data-field="' + fields[i + 1] + '"]').focus();
                                return;
                            }
                        }
                        $('#product').focus();
                    }
                });
            }
        }
    };
});

dApp.factory('Purchase', ['Rest', function (Rest) {
        return Rest('purchase/:id');
    }]);

dApp.factory('Movement', ['Rest', function (Rest) {
        return Rest('movement/:id');
    }]);

dApp.factory('Sales', ['Rest', function (Rest) {
        return Rest('sales/:id');
    }]);
