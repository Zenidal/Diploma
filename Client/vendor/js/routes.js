diplomaApp.config(['$routeProvider', '$httpProvider', function ($routeProvider, $httpProvider) {
    $httpProvider.interceptors.push('ResponseObserverService');
    $httpProvider.interceptors.push('AuthInterceptorService');
    $routeProvider
        .when('/home',
            {
                templateUrl: 'vendor/views/home.html',
                controller: 'HomeCtrl as vm'
            })
        .when('/games',
            {
                templateUrl: 'vendor/views/games.html',
                controller: 'GamesCtrl as vm',
                resolve: {
                    isUser: isUser,
                    isGameExists: ['$http', '$location', '$q', 'NotificationService', 'PATHS', function ($http, $location, $q, NotificationService, PATHS) {
                        var deferred = $q.defer();
                        var isExist = isActualGameExist($http, NotificationService, PATHS);

                        if (isExist) {
                            deferred.reject();
                            $location.path('/actual_game');
                        } else {
                            deferred.resolve();
                        }
                        return deferred.promise;
                    }]
                }
            })
        .when('/profile',
            {
                templateUrl: 'vendor/views/profile.html',
                controller: 'ProfileCtrl as vm',
                resolve: {
                    isUser: isUser
                }
            })
        .when('/actual_game',
            {
                templateUrl: 'vendor/views/gameTable.html',
                resolve: {
                    isUser: isUser,
                    isGameExists: ['$http', '$location', 'NotificationService', 'PATHS', '$q', function ($http, $location, NotificationService, PATHS, $q) {
                        var deferred = $q.defer();
                        var isExist = isActualGameExist($http, NotificationService, PATHS);

                        if (isExist) {
                            deferred.resolve();
                        } else {
                            deferred.reject();
                            $location.path('/games');
                        }
                        return deferred.promise;
                    }]
                }
            })
        .otherwise({
            redirectTo: '/home'
        });
}]);