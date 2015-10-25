'use strict';

var diplomaApp = angular.module('diplomaApp', [
    'ngRoute',
    'ngResource',
    'diplomaControllers'
]);
var diplomaControllers = angular.module('diplomaControllers', []);

angular.module('diplomaApp')
    .run(['$rootScope', 'AppUserService', function ($rootScope, AppUserService) {
        $rootScope.isViewLoading = false;
        $rootScope.$on('$routeChangeStart', function () {
            $rootScope.isViewLoading = true;
        });
        $rootScope.$on('$routeChangeSuccess', function () {
            $rootScope.isViewLoading = false;
        });
        $rootScope.$on('$routeChangeError', function () {
            $rootScope.isViewLoading = false;
        });
        if (localStorage['user.apiKey']) {
            AppUserService.addUser(
                localStorage['user.username'],
                localStorage['user.roleName'],
                localStorage['user.apiKey'],
                localStorage['user.id']
            );
        }
    }]);