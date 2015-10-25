diplomaApp.config(['$routeProvider', '$httpProvider', function ($routeProvider, $httpProvider) {
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
                        .then(function (responce) {
                            return responce.games;
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
        .otherwise({
            redirectTo: '/home'
        });
}]);