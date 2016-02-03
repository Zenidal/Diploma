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
                games: ['GameService', function (GameService) {
                    return GameService.resource.get().$promise
                        .then(function (response) {
                            return response.games;
                        })
                }],
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
        .when('/test',
        {
            templateUrl: 'vendor/views/gameTable.html'
        })
        .otherwise({
            redirectTo: '/home'
        });
}]);