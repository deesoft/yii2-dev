module.factory('Product', ['Rest', function (Rest) {
        return Rest('product/:id');
    }]);

resolves.product = {
    query: ['Product', '$location',
        function (Product, $location) {
            return Product.query(angular.extend({}, $location.search(), {
                expand: 'group,category',
            }));
        }],
    view: ['Product', '$route', function (Product, $route) {
            return Product.get({
                id: $route.current.params.id,
                expand: 'group,category',
            });
        }],
}

