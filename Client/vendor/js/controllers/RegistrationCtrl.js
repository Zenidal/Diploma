'use strict';

diplomaControllers.controller('RegistrationCtrl', ['$scope', '$location', 'UserService',
    function ($scope, $location, UserService) {
        $scope.register = register;

        function register() {
            if ($scope.user.password != $scope.user.passwordConfirmation) {
                $scope.passwordMismatch = true;
            } else {
                var User = new UserService;
                User.username = $scope.user.username;
                User.password = $scope.user.password;
                User.passwordConfirmation = $scope.user.passwordConfirmation;
                User.$save(
                    function (data) {
                        if (data.errorMessage !== null) {
                            $scope.hasError = true;
                            $scope.error = data.errorMessage;
                        }
                        if (data.message !== null) {
                            $scope.hasMessage = true;
                            $scope.message = data.message;
                        }
                    },
                    function (error) {
                        $scope.hasError = true;
                        $scope.error = error;
                    }
                );
            }
        }
    }
]);