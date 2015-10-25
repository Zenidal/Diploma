(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('HeaderCtrl', HeaderCtrl);

    HeaderCtrl.$inject = ['$rootScope', '$http', '$location', 'NotificationService', 'AppUserService', 'UserService', 'PATHS'];

    function HeaderCtrl($rootScope, $http, $location, NotificationService, AppUserService, UserService, PATHS) {
        var vm = this;
        vm.logout = function () {
            if ($rootScope.user && $rootScope.user.isAuthorized) {
                NotificationService.addMessage('You successfully logged out.');
                AppUserService.removeUser();
                $location.path( "/home" );
            }
        };
        vm.authorize = function () {
            $http({
                method: 'POST',
                url: PATHS.SERVER_PATH + '/authorize',
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
                    $location.path( "/home" );
                }
                $('#singInModal').modal('hide');
            }, function errorCallback(response) {
                NotificationService.addErrorMessage(response.data.errorMessage);
                $('#singInModal').modal('hide');
            });
        };
        vm.register = function () {
            if (vm.user.password != vm.user.passwordConfirmation) {
                NotificationService.addErrorMessage('Passwords mismatch.');
            } else {
                var User = new UserService;
                User.username = vm.user.username;
                User.password = vm.user.password;
                User.passwordConfirmation = vm.user.passwordConfirmation;
                User.$save(
                    function (response) {
                        if (response.errorMessage !== undefined && response.errorMessage !== null) {
                            NotificationService.addErrorMessage(response.errorMessage);
                        }
                        if (response.message !== undefined && response.message !== null) {
                            NotificationService.addMessage(response.message);
                            $('#singUpModal').modal('hide');
                        }
                    },
                    function (response) {
                        NotificationService.addErrorMessage(response.errorMessage);
                        $('#singUpModal').modal('hide');
                    }
                );
            }
        };
    }
})();