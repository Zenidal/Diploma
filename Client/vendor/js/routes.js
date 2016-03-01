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
                        return isActualGameExist($http, NotificationService, PATHS).then(function (isExist) {
                            if (isExist) {
                                $location.path('/actual_game');
                                return $q.reject();
                            } else {
                                return $q.resolve();
                            }
                        }).promise;
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
                controller: 'ActualGameCtrl as vm',
                resolve: {
                    isUser: isUser,
                    isGameExists: ['$http', '$location', 'NotificationService', 'PATHS', '$q', function ($http, $location, NotificationService, PATHS, $q) {
                        return isActualGameExist($http, NotificationService, PATHS).then(function (isExist) {
                            if (isExist) {
                                return $q.resolve();
                            } else {
                                $location.path('/games');
                                return $q.reject();
                            }
                        }).promise;
                    }],
                    game: ['$q', '$http', 'PATHS', function ($q, $http, PATHS) {
                        return $http({
                            method: 'GET',
                            url: PATHS.SERVER_PATH + '/game'
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