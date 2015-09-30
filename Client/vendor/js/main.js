'use strict';

var diplomaApp = angular.module('diplomaApp', ['ui.router', 'ngRoute', 'ngResource', 'diplomaControllers']);
var diplomaControllers = angular.module('diplomaControllers', []);

diplomaApp.run(['$rootScope', '$http', function ($rootScope, $http) {
    $rootScope.serverPath = 'http://gvint.api.loc:666';
}]);