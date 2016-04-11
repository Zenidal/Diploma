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
                    isUser: isUser
                }
            })
        .when('/profile',
            {
                templateUrl: 'vendor/views/profile.html',
                controller: 'ProfileCtrl as vm',
                resolve: {
                    isUser: isUser,
                    info: ['$q', '$http', '$rootScope', 'PATHS', function ($q, $http, $rootScope, PATHS) {
                        var deferred = $q.defer();
                        $http({
                            method: 'GET',
                            url: PATHS.SERVER_PATH + '/users/' + $rootScope.user.id + '/profile'
                        }).then(function successCallback(response) {
                            deferred.resolve(response.data);
                        });
                        return deferred.promise;
                    }]
                }
            })
        .when('/actualGame/:id',
            {
                templateUrl: 'vendor/views/gameTable.html',
                controller: 'ActualGameCtrl as vm',
                resolve: {
                    isUser: isUser,
                    actualGame: ['$q', '$http', '$location', '$route', 'PATHS', 'NotificationService',
                        function ($q, $http, $location, $route, PATHS, NotificationService) {
                            var deferred = $q.defer();
                            $http({
                                method: 'GET',
                                url: PATHS.SERVER_PATH + '/games/' + $route.current.params.id
                            }).then(function successCallback(response) {
                                if (response.data.errorMessage) {
                                    NotificationService.addErrorMessage(response.data.errorMessage);
                                    deferred.reject();
                                    $location.path("#/games");
                                }
                                deferred.resolve(response.data.game ? response.data.game : null);
                            }, function errorCallback(response) {
                                NotificationService.addErrorMessage(response.data.errorMessage);
                                deferred.reject();
                                $location.path("#/games");
                            });
                            return deferred.promise;
                        }]
                }
            })
        .otherwise({
            redirectTo: '/home'
        });
}]);