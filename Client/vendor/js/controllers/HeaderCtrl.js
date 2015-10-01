(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('HeaderCtrl', HeaderCtrl);

    HeaderCtrl.$inject = ['$rootScope', 'NotificationService', 'AppUserService'];

    function HeaderCtrl($rootScope, NotificationService, AppUserService) {
        var vm = this;
        vm.logout = function () {
            if ($rootScope.user && $rootScope.user.isAuthorized) {
                NotificationService.addMessage('You successfully logged out.');
                AppUserService.removeUser();
                $rootScope.user = undefined;
            }
        }
    }
})();