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
                    function (data) {
                        if (data.errorMessage !== undefined && data.errorMessage !== null) {
                            NotificationService.addErrorMessage(data.errorMessage);
                        }
                        if (data.message !== undefined && data.message !== null) {
                            console.log(data.message);
                            NotificationService.addMessage(data.message);
                        }
                    },
                    function (error) {
                        NotificationService.addErrorMessage(data.errorMessage);
                    }
                );
            }
        }
    }
})();