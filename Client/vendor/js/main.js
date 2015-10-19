'use strict';

var diplomaApp = angular.module('diplomaApp', [
    'ngRoute',
    'ngResource',
    'diplomaControllers'
]);
var diplomaControllers = angular.module('diplomaControllers', []);

angular.module('diplomaApp')
    .run(['$rootScope', 'AppUserService', function ($rootScope, AppUserService) {
        $rootScope.serverPath = 'http://gvint.api.loc:666';
        if (localStorage['user.apiKey']) {
            AppUserService.addUser(
                localStorage['user.username'],
                localStorage['user.roleName'],
                localStorage['user.apiKey'],
                localStorage['user.id']
            );
        }
    }]);