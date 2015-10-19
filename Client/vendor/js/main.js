'use strict';

var diplomaApp = angular.module('diplomaApp', [
    'ngRoute',
    'ngResource',
    'diplomaControllers'
]);
var diplomaControllers = angular.module('diplomaControllers', []);

angular.module('diplomaApp')
    .run(['$rootScope', function ($rootScope) {
        $rootScope.serverPath = 'http://gvint.api.loc:666';
    }]);