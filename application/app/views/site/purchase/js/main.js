module.factory('Purchase', ['Rest', function (Rest) {
        return Rest('purchase');
    }]);

ResolvePurchaseQuery.$inject = ['Purchase', '$location'];
function ResolvePurchaseQuery(Purchase, $location){
    return Purchase.query(angular.extend({}, $location.search(), {
            expand: 'supplier,branch',
        }));
}
