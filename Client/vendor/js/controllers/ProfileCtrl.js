(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('ProfileCtrl', ProfileCtrl);

    ProfileCtrl.$inject = ['$rootScope'];

    function ProfileCtrl($rootScope) {
        var vm = this;
    }
})();