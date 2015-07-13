'use strict';

diplomaControllers.controller('RegistrationCtrl', ['$scope', '$location', 'UserService',
    function ($scope, $location, UserService) {
        $scope.register = register;

        function register(){
            UserService.Create($scope.user)
                .then(function (response) {
                    if (response.success) {
                        $location.path('/login');
                    } else {
                        $scope.dataLoading = false;
                    }
                });
        }
    }
]);