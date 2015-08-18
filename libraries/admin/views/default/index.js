dAdmin.factory('Assignment', ['$resource', function ($resource) {

        return $resource(dAdmin.prefixUrl + 'assignment/:id', {}, {
            assign: {method: 'POST', url:dAdmin.prefixUrl + 'assignment/assign/:id'},
            revoke: {method: 'POST', url:dAdmin.prefixUrl + 'assignment/revoke/:id'},
        });
    }]);

dAdmin.factory('Item', ['$resource', function ($resource) {

        return $resource(dAdmin.prefixUrl + 'item/:id', {}, {
            assign: {method: 'POST', url:dAdmin.prefixUrl + 'item/assign/:id'},
            revoke: {method: 'POST', url:dAdmin.prefixUrl + 'item/revoke/:id'},
        });
    }]);
