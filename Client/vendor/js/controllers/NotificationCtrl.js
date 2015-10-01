(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('NotificationCtrl', NotificationCtrl);

    NotificationCtrl.$inject = ['NotificationService'];

    function NotificationCtrl(NotificationService) {
        var vm = this;

        vm.errors = NotificationService.errors;
        vm.messages = NotificationService.messages;
    }
})();