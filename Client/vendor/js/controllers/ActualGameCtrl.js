(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('ActualGameCtrl', ActualGameCtrl);

    ActualGameCtrl.$inject = ['NotificationService'];

    function ActualGameCtrl(NotificationService) {
        var vm = this;
    }
})();