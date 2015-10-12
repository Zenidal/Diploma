diplomaApp.config(['$stateProvider', '$httpProvider', function ($stateProvider, $httpProvider) {
    $httpProvider.interceptors.push('AuthInterceptorService');
    $stateProvider
        .state('root',
        {
            url: '',
            abstract: true,
            templateUrl: 'vendor/views/home.html'
        })
        .state('root.home',
        {
            url: '/',
            templateUrl: 'vendor/views/home.html',
            controller: 'HomeCtrl as vm'
        })
        .state('root.games',
        {
            url: '/games',
            templateUrl: 'vendor/views/games.html',
            controller: 'GamesCtrl as vm'
        })
}]);