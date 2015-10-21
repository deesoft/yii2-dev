module.factory('Item', ['Rest', function (Rest) {
        return Rest('item/:id', {
            actions: {
                //query:{postProcess:false},
                assign: {method: 'POST', url: 'item/assign/:id'},
                revoke: {method: 'POST', url: 'item/revoke/:id'},
            }
        });
    }]);

ResolveRoleQuery.$inject = ['Item', '$route'];
function ResolveRoleQuery(Item, $route) {
    return Item.query({type: $route.current.params.type});
}

ResolveRoleView.$inject = ['Item', '$route'];
function ResolveRoleView(Item, $route) {
    return Item.get({
        type:$route.current.params.type,
        id: $route.current.params.id,
        expand: 'children,avaliables'
    });
}