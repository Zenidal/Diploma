(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('RegistrationCtrl', RegistrationCtrl);

    RegistrationCtrl.$inject = ['UserService', 'NotificationService'];

    function RegistrationCtrl(UserService, NotificationService) {
        var vm = this;
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
                        }
                    },
                    function (response) {
                        NotificationService.addErrorMessage(response.errorMessage);
                    }
                );
            }
        }
    }
})();