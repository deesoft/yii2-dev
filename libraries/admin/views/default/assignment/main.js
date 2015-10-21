module.factory('Assignment', ['Rest', function (Rest) {
        return Rest('assignment/:id', {
            actions: {
                assign: {method: 'POST', url: 'assignment/assign/:id'},
                revoke: {method: 'POST', url: 'assignment/revoke/:id'},
            }
        });
    }]);

ResolveAssignmnetQuery.$inject = ['Assignment', '$location'];
function ResolveAssignmnetQuery(Assignment, $location) {
    return Assignment.query($location.search());
}

ResolveAssignmnetView.$inject = ['Assignment', '$route'];
function ResolveAssignmnetView(Assignment, $route) {
    return Assignment.get({
        id: $route.current.params.id,
    });
}
