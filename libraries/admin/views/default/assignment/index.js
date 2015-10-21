$scope.search = $location.search();

$scope.rows = data.data;
$scope.pagination = data._meta;
$scope.setSearch = setSearch;

doFocus('q');

function setSearch(field,value){
    if(value==''){
        value = undefined;
    }
    $location.search(field,value);
}

