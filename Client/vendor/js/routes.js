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
                    isUser: isUser
                }
            })
        .when('/actualGame/:id',
            {
                templateUrl: 'vendor/views/gameTable.html',
                controller: 'ActualGameCtrl as vm',
                resolve: {
                    isUser: isUser,
                    actualGame: ['$q', '$http', 'PATHS', '$route', function ($q, $http, PATHS, $route) {
                        return $http({
                            method: 'GET',
                            url: PATHS.SERVER_PATH + '/games/' + $route.current.params.id
                        }).then(function successCallback(response) {
                            return response.data.game ? response.data.game : null;
                        }, function errorCallback(response) {
                            NotificationService.addErrorMessage(response.data.errorMessage);
                            return $q.reject.promise;
                        });
                    }]
                }
            })
        .otherwise({
            redirectTo: '/home'
        });
}]);