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
        .state('register', {
            url: '/signup',
            templateUrl: 'vendor/views/registration.html',
            controller: 'RegistrationCtrl as vm'
        })
        .state('authorize', {
            url: '/signin',
            templateUrl: 'vendor/views/authorization.html',
            controller: 'AuthorizationCtrl as vm'
        });
}]);