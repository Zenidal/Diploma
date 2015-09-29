(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('NotificationCtrl', NotificationCtrl);

    NotificationCtrl.$inject = ['NotificationService', '$scope'];

    function NotificationCtrl(NotificationService, $scope) {
        var vm = this;

        vm.errors = NotificationService.errors;
        vm.messages = NotificationService.messages;
    }
})();