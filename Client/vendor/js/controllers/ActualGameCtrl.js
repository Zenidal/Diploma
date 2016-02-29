(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('ActualGameCtrl', ActualGameCtrl);

    ActualGameCtrl.$inject = ['NotificationService', 'PATHS'];

    function ActualGameCtrl(NotificationService, PATHS) {
        var vm = this;

        vm.webSocket = WS.connect(PATHS.SOCKET_PATH);
        vm.webSocket.on("socket/connect", function (session) {
            var channel = 'actual_game/channel';
            session.subscribe(channel, function (uri, payload) {
            });
        });
    }
})();