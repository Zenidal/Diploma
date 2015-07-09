var gvintApp = angular.module('gvintApp', ['angular-route', 'gvintControllers', 'ngResource']);
var gvintControllers = angular.module('gvintControllers', []);

gvintApp.config(['$routeProvider', function ($routeProvider) {
    $routeProvider
        .when('/registration', {
            templateUrl: 'views/registration.html',
            controller: 'RegistrationCtrl'
        })
        .otherwise({
            redirectTo: '/'
        });
}]);