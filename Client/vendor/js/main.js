var gvintApp = angular.module('gvintApp', [require('angular-route', 'gvintControllers', 'ngResource')]);

gvintApp.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider.
            when('/registration', {
                templateUrl: 'views/registration.html',
                controller: 'RegistrationCtrl'
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);

var gvintControllers = angular.module('gvintControllers', []);