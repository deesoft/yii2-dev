module.factory('Item', ['Rest', function (Rest) {
        return Rest('item/:id', {
            actions: {
                //query:{postProcess:false},
                assign: {method: 'POST', url: 'item/assign/:id'},
                revoke: {method: 'POST', url: 'item/revoke/:id'},
            }
        });
    }]);

ResolveRoleQuery.$inject = ['Item'];
function ResolveRoleQuery(Item) {
    return Item.query({type: 1});
}

ResolveRoleView.$inject = ['Item', '$route'];
function ResolveRoleView(Item, $route) {
    return Item.get({
        id: $route.current.params.id,
        expand: 'assignments,avaliables'
    });
}