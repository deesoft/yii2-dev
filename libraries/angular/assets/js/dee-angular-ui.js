(function () {
    var module = angular.module('dee.ui', []);

    module.directive('dSort', dSortDirective);

    dSortDirective.$inject = ['$parse'];
    function dSortDirective() {
        return {
            restrict: 'A',
            scope: {
                value: '=dSort',
                multiple: '=multisort',
            },
            controller: ctrl,
        };

        function strToArray(sort, multiple) {
            if (sort === undefined || sort == '') {
                return {};
            }
            var res = {};
            var s = sort.split();
            if (!multiple) {
                s = [s[0]];
            }
            angular.forEach(s.reverse(), function (f) {
                if (f[0] == '-') {
                    res[f.substr(1)] = -1;
                } else {
                    res[f] = 1;
                }
            });
            return res;
        }

        function arrayToStr(sort, multiple) {
            var a = [], c = 0;

            angular.forEach(sort, function (v, f) {
                a.push((v == 1 ? '' : '-') + f);
                c++;
            });
            if (c == 0) {
                return undefined;
            }
            if (multiple) {
                return a.reverse().join();
            } else {
                return a[c - 1];
            }
        }

        ctrl.$inject = ['$scope', '$attrs'];
        function ctrl($scope, $attrs) {
            var $fields = {};
            var $sorts = {};

            $scope.$watch('value', function (s) {
                $sorts = strToArray(s, $scope.multiple);
                angular.forEach($fields, function (child, f) {
                    if (angular.isDefined($sorts[f])) {
                        child.set($sorts[f]);
                    } else {
                        child.set(0);
                    }
                });
                if (angular.isDefined($attrs.dChange)) {
                    $scope.$parent.$eval($attrs.dChange, {'$value': $scope.value});
                }
            });

            this.addField = function (field, child) {
                $fields[field] = child;
                if (!angular.isDefined($sorts[field])) {
                    child.set($sorts[field]);
                }
            }

            this.toggleState = function (field) {
                var current = angular.isDefined($sorts[field]) ? $sorts[field] : 0;
                delete $sorts[field];
                if (current === 0) {
                    $sorts[field] = 1;
                } else if (current === 1) {
                    $sorts[field] = -1;
                }
                $scope.value = arrayToStr($sorts, $scope.multiple);
                $scope.$apply();
            }
        }
    }

    module.directive('sortField', [function () {
            return {
                require: '^dSort',
                scope: {
                    field: '@sortField'
                },
                link: function (scope, element, attrs, sortCtrl) {
                    var child = {
                        value: 0,
                        set: function (value) {
                            if (child.value !== value) {
                                child.value = value;
                                element.removeClass('asc desc');
                                if (value === -1) {
                                    element.addClass('desc');
                                } else if (value === 1) {
                                    element.addClass('asc');
                                }
                            }
                        }
                    }

                    sortCtrl.addField(scope.field, child);

                    element.on('click', function () {
                        sortCtrl.toggleState(scope.field);
                    });
                }
            }
        }]);
    
    module.directive('page',function(){
        return {
            scope: {
                title: '@'
            },
            link: function (scope) {
                scope.$watch('title', function (val) {
                    window.document.title = val;
                });
            }
        }
    });

    module.directive('focusMe', function () {
        return function (scope, elem, attr) {
            scope.$on('focusOn', function (e, name) {
                if (name === attr.focusMe) {
                    elem[0].focus();
                }
            });
        };
    });

    module.factory('doFocus', function ($rootScope, $timeout) {
        return function (name) {
            $timeout(function () {
                $rootScope.$broadcast('focusOn', name);
            });
        }
    });
})();
