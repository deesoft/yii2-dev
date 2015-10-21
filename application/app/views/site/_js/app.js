var opts = options;
var baseApiUrl = options.baseApiUrl;
var TOKEN_KEY = CryptoJS.MD5('d426_angular_token');
var resolves = {};

module.run(['$rootScope', function ($rootScope) {
        $rootScope.Page = {};

        $rootScope.onLoading = false;
        $rootScope.$on('$routeChangeStart', function () {
            $rootScope.onLoading = true;
        });
        $rootScope.$on('$routeChangeSuccess', function () {
            $rootScope.onLoading = false;
        });
        $rootScope.$on('$routeChangeError', function () {
            $rootScope.onLoading = false;
        });
    }]);

module.directive('navMenu', ['$location', function ($location) {
        return function (scope, element, attrs) {
            var links = jQuery(element).find('a[href]'),
                urlMap = {},
                activeClass = attrs.navMenu || 'active';

            links.each(function () {
                var $link = jQuery(this);
                var url = $link.attr('href');

                if (url.substring(0, 1) === '#') {
                    urlMap[url.substring(1)] = $link;
                } else {
                    urlMap[url] = $link;
                }
            });

            scope.$on('$routeChangeSuccess', function () {
                var $link = urlMap[$location.path()];
                jQuery(element).find('li').removeClass(activeClass);

                if ($link) {
                    $link.parents('li').addClass(activeClass);
                }
            });
        }
    }]);