(function () {
    var module = angular.module('dee.rest', []);

    module.provider('Rest', RestProvider);

    function RestProvider() {
        var provider = this;

        this.defaults = {
            baseUrl: undefined,
            collectionEnvelope: undefined,
            metaEnvelope: '_meta',
            arrayAsRest: false,
            pagerHeaderMap: {
                totalCount: 'X-Pagination-Total-Count',
                pageCount: 'X-Pagination-Page-Count',
                currentPage: 'X-Pagination-Current-Page',
                perPage: 'X-Pagination-Per-Page',
            },
            actions: {
                query: {method: 'get', postProcess: asArray},
                get: {method: 'get', postProcess: asResource},
                save: {method: 'post', postProcess: asResource},
                update: {method: 'put', postProcess: asResource},
                remove: {method: 'delete', },
                patch: {method: 'patch', postProcess: asResource},
            }
        };

        this.$get = rest;

        rest.$inject = ['$http'];
        function rest($http) {
            return function (path, opt) {
                var $opts = angular.merge({}, provider.defaults, opt || {});
                var $globalUrl = new UrlMatcher(applyPath(path, $opts.baseUrl));
                
                function Resource(data) {
                    var th = this;
                    for (var k in th) {
                        if (k.charAt(0) != '$' && k.charAt(0) != '_') {
                            delete th[k];
                        }
                    }

                    for (var k in data) {
                        if (data.hasOwnProperty(k)) {
                            th[k] = data[k];
                        }
                    }
                }

                angular.forEach($opts.actions, function (action, name) {
                    var hasBody = /^(POST|PUT|PATCH)$/i.test(action.method);
                    var urlMatcher = action.url ? new UrlMatcher(applyPath(action.url, $opts.baseUrl)) : $globalUrl;

                    Resource[name] = function (params, data, config) {
                        if (!hasBody) {
                            config = data || {};
                            data = undefined;
                        }
                        var _params = angular.copy(params || {});
                        var httpConfig = angular.merge({}, config || {}, {
                            method: action.method,
                            url: urlMatcher.format(_params),
                            params: _params,
                            data: data,
                        });

                        return $http(httpConfig).then(function (r) {
                            if (action.postProcess) {
                                if (typeof action.postProcess === 'string' && postProcess[action.postProcess]) {
                                    return postProcess[action.postProcess](r, urlMatcher.parameters(), Resource, $opts);
                                } else {
                                    return action.postProcess(r, urlMatcher.parameters(), Resource, $opts);
                                }
                            } else {
                                return r;
                            }
                        });
                    }

                    Resource.prototype['$' + name] = function (params, data, config) {
                        var th = this, _data;
                        if (hasBody) {
                            if (data) {
                                _data = data;
                            } else {
                                _data = angular.extend({}, th);
                                for (var k in _data) {
                                    if (k.charAt(0) == '$') {
                                        delete _data[k];
                                    }
                                }
                            }
                        } else {
                            config = data || {};
                        }

                        var _params = angular.extend({}, th.$urlParams || {});

                        angular.extend(_params, params || {});
                        var httpConfig = angular.merge({}, config || {}, {
                            method: action.method,
                            url: urlMatcher.format(_params),
                            params: _params,
                            data: _data,
                        });

                        return $http(httpConfig).then(function (r) {
                            if (action.postProcess) {
                                if (typeof action.postProcess === 'string' && postProcess[action.postProcess]) {
                                    return postProcess[action.postProcess](r, urlMatcher.parameters(), Resource, $opts);
                                } else {
                                    return action.postProcess(r, urlMatcher.parameters(), Resource, $opts);
                                }
                            } else {
                                return r;
                            }
                        });
                    }
                });

                return Resource;
            }
        }

        function asArray(r, urlParams, Resource, opts) {
            if (opts.collectionEnvelope) {
                var rows = r.data[opts.collectionEnvelope];
                if (opts.arrayAsRest) {
                    for (var i in rows) {
                        rows[i] = asResource({data: rows[i]}, urlParams, Resource, opts);
                    }
                }
                angular.forEach(r.data, function (val, key) {
                    if (key != opts.collectionEnvelope) {
                        r[key] = val;
                    }
                });
                if (opts.metaEnvelope !== '_meta') {
                    r._meta = r[opts.metaEnvelope];
                }
                r.data = rows;
                return r;
            } else {
                var callback = r.headers;
                if (opts.arrayAsRest) {
                    for (var i in r.data) {
                        r.data[i] = asResource({data: r.data[i]}, urlParams, Resource, opts);
                    }
                }
                r._meta = r[opts.metaEnvelope] || {};
                angular.forEach(opts.pagerHeaderMap, function (val, key) {
                    r._meta[key] = callback(val);
                });
                return r;
            }
        }

        function asResource(r, urlParams, Resource) {
            var res = new Resource(r.data);
            res.$urlParams = {};
            angular.forEach(urlParams, function (p) {
                res.$urlParams[p] = r.data[p];
            });
            return res;
        }

        var postProcess = {
            asArray: asArray,
            asResource: asResource,
        };

        function applyPath(path, baseUrl) {
            var RE = /^(?:[a-z]+:\/)?\//i;
            return (baseUrl == undefined || RE.test(path)) ? path : baseUrl + path;
        }

        function UrlMatcher(url) {
            var th = this;
            this.$url = url;
            this.$params = [];
            angular.forEach(url.match(/:(\w+)/g), function (v) {
                th.$params.push(v.substr(1));
            });
        }

        UrlMatcher.prototype.format = function (params, leave) {
            var url = this.$url;
            return url.replace(/(\/)?:(\w+)/g, function (_, slash, key) {
                var s = (params[key] === undefined || params[key] === null) ? '' : (slash || '') + params[key];
                if(!leave){
                    delete params[key];
                }
                return s;
            });
        }
        
        UrlMatcher.prototype.parameters = function (){
            return this.$params;
        }
    }
})();