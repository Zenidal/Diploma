(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('ProfileCtrl', ProfileCtrl);

    ProfileCtrl.$inject = ['$rootScope', 'info'];

    function ProfileCtrl($rootScope, info) {
        var vm = this;
        vm.info = info;
    }
})();