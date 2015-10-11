diplomaApp.config(['$stateProvider', '$httpProvider', function ($stateProvider, $httpProvider) {
    $httpProvider.interceptors.push('AuthInterceptorService');
    $stateProvider
        .state('index',
        {
            url: '',
            abstract: true
        })
        .state('home',
        {
            url: '/homepage',
            templateUrl: 'vendor/views/home.html',
            controller: 'HomeCtrl as vm'
        })
}]);