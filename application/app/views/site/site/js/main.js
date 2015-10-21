testResolve.$inject = ['$q', '$location'];
function testResolve(q,loc){
    var defer = q.defer();
    setTimeout(function(){
        var s = loc.search();
        if(s.type=='e'){
            defer.reject('gak jadi');
        }else{
            defer.resolve(loc.search());
        }
    },2000);
    return defer.promise.then(function(r){return r;},function(r){return r;});
}

function loadPage(params){
    return 'template/' + params.page;
}