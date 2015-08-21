dAdmin.factory('Assignment', ['$resource', function ($resource) {

        return $resource(dAdmin.prefixUrl + 'assignment/:id', {}, {
            assign: {method: 'POST', url: dAdmin.prefixUrl + 'assignment/assign/:id'},
            revoke: {method: 'POST', url: dAdmin.prefixUrl + 'assignment/revoke/:id'},
        });
    }]);

dAdmin.factory('Item', ['$resource', function ($resource) {

        return $resource(dAdmin.prefixUrl + 'item/:id', {}, {
            assign: {method: 'POST', url: dAdmin.prefixUrl + 'item/assign/:id'},
            revoke: {method: 'POST', url: dAdmin.prefixUrl + 'item/revoke/:id'},
            update: {method: 'PUT'},
        });
    }]);

dAdmin.factory('Rule', ['$resource', function ($resource) {

        return $resource(dAdmin.prefixUrl + 'rule/:id', {}, {
        });
    }]);

dAdmin.factory('Route', ['$resource', function ($resource) {

        return $resource(dAdmin.prefixUrl + 'route/:id',{}, {
            query: {method: 'GET', isArray: false},
            add: {method: 'POST'},
            remove: {method: 'DELETE'},
        });
    }]);

dAdmin.filter('escape', function () {
    return window.encodeURI;
});