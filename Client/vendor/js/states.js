diplomaApp.config(['$stateProvider', function ($stateProvider) {
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
        });
}]);