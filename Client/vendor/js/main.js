'use strict';

var diplomaApp = angular.module('diplomaApp',
    ['ngRoute', 'ngResource', 'diplomaControllers']);
var diplomaControllers = angular.module('diplomaControllers', []);

diplomaApp.config(['$routeProvider', '$provide', function ($routeProvider, $provide) {
    $routeProvider
        .when('/',
        {
            redirectTo: '/home'
        })
        .when('',
        {
            redirectTo: '/home'
        })
        .when('/home',
        {
            templateUrl: 'vendor/views/home.html',
            controller: 'HomeCtrl'
        })
        .when('/registration', {
            templateUrl: 'vendor/views/registration.html',
            controller: 'RegistrationCtrl'
        })
        .otherwise(
        {
            redirectTo: '/'
        });
}]);

diplomaApp.run(['$rootScope', function($rootScope){
    $rootScope.serverPath = 'http://127.0.0.1:8000';
}]);