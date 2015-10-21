module.factory('Route',['Rest',function(Rest){
        return Rest('route',{
            actions:{
                add:{method:'POST', url:'route/add'},
                remove:{method:'POST', url:'route/remove'}
            }
        });
}]);