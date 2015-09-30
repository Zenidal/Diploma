(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('AuthorizationCtrl', AuthorizationCtrl);

    AuthorizationCtrl.$inject = ['$rootScope', '$http', 'NotificationService', 'AppUserService'];

    function AuthorizationCtrl($rootScope, $http, NotificationService, AppUserService) {
        var vm = this;
        vm.authorize = function () {
            $http({
                method: 'POST',
                url: $rootScope.serverPath + '/authorize',
                data: {
                    username: vm.user.username,
                    password: vm.user.password
                }
            }).then(function successCallback(response) {
                if (response.data.errorMessage !== undefined && response.data.errorMessage !== null) {
                    NotificationService.addErrorMessage(response.data.errorMessage);
                }
                if (response.data.message !== undefined && response.data.message !== null) {
                    NotificationService.addMessage(response.data.message);
                    AppUserService.addUser(response.data.username, response.data.roleName, response.data.apiKey, response.data.id);
                }
            }, function errorCallback(response) {
                NotificationService.addErrorMessage(response.data.errorMessage);
            });
        }
    }
})();