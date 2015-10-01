'use strict';

var diplomaApp = angular.module('diplomaApp', ['ui.router', 'ngRoute', 'ngResource', 'diplomaControllers']);
var diplomaControllers = angular.module('diplomaControllers', []);

diplomaApp.run(['$rootScope', '$state', '$stateParams', function ($rootScope, $state, $stateParams) {
    $rootScope.serverPath = 'http://gvint.api.loc:666';
    $rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;
    var openLinks = [
        'home',
        'register',
        'authorize'
    ];

    var closeLinksToLoggedUser = [
        'register',
        'authorize'
    ];

    $rootScope.$on('$stateChangeStart', function (e, toState, toParams, fromState, fromParams) {
        if ($rootScope.user && $rootScope.user.isAuthorized) {
            if ($.inArray(toState.name, closeLinksToLoggedUser) !== -1) {
                $rootScope.$state.go('home');
                event.preventDefault();
            }
        }
    });
}]);