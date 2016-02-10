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
                    gameExists: ['$http', '$location', 'NotificationService', 'PATHS', function ($http, $location, NotificationService, PATHS) {
                        $http({
                            method: 'GET',
                            url: PATHS.SERVER_PATH + '/checkGame'
                        }).then(function successCallback(response) {
                            if (response.data.isExist) {
                                $location.path('/actual_game');
                            }
                        }, function errorCallback(response) {
                            NotificationService.addErrorMessage(response.data.errorMessage);
                        });
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
                    gameExists: ['$http', '$location', 'NotificationService', 'PATHS', function ($http, $location, NotificationService, PATHS) {
                        $http({
                            method: 'GET',
                            url: PATHS.SERVER_PATH + '/checkGame'
                        }).then(function successCallback(response) {
                            if (!response.data.isExist) {
                                $location.path('/games');
                            }
                        }, function errorCallback(response) {
                            NotificationService.addErrorMessage(response.data.errorMessage);
                        });
                    }]
                }
            })
        .otherwise({
            redirectTo: '/home'
        });
}]);