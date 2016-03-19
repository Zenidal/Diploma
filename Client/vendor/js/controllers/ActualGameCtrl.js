(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('ActualGameCtrl', ActualGameCtrl);

    ActualGameCtrl.$inject = ['$rootScope', '$location', 'NotificationService', 'PATHS', 'game'];

    function ActualGameCtrl($rootScope, $location, NotificationService, PATHS, game) {
        var vm = this;
        vm.game = game;
        vm.myTurn = false;
        vm.creator = false;
        vm.visitor = false;

        vm.init = init;
        vm.moveCard = moveCard;
        vm.updateField = updateField;

        vm.init();
        vm.updateField();

        //functions
        function init() {
            if (!vm.game.visitorId || !vm.game.creatorId) {
                $location.path('games');
            }
            vm.socketChannel = 'actual_game/' + vm.game.id;
            vm.game.json = JSON.parse(vm.game.json);

            var webSocket = WS.connect(PATHS.SOCKET_PATH);
            webSocket.on("socket/connect", function (session) {
                session.subscribe(vm.socketChannel, function (uri, payload) {
                    if (payload.msg && payload.msg.game) {
                        vm.game = payload.msg.game;
                        vm.game.json = JSON.parse(vm.game.json);
                        vm.updateField();
                        $rootScope.$apply();
                    }
                });
            });

            $(document).ready(function () {
                $('.target').sortable({
                    items: '.target:even'
                });

                $('#cards-line').sortable({
                    connectWith: '.target',
                    revert: true,
                    remove: function (event, ui) {
                        vm.moveCard(ui.item);
                    },
                    sort: function (event, ui) {
                    }
                });
            });
        }

        function moveCard(item) {
            var webSocket = WS.connect(PATHS.SOCKET_PATH);
            webSocket.on("socket/connect", function (session) {
                session.publish(vm.socketChannel, {
                    userId: $rootScope.user.id,
                    cardId: item[0].id.replace('blank', ''),
                    gameId: vm.game.id,
                    event: 'move'
                });
            });

            item.removeClass('card-blank');
            item.addClass('realized-card-blank');
        }

        function updateField() {
            if (vm.game.creatorId == $rootScope.user.id) {
                vm.creator = true;
                vm.visitor = false;
            }
            if (vm.game.visitorId == $rootScope.user.id) {
                vm.creator = false;
                vm.visitor = true;
            }
            vm.myTurn = !!((vm.creator && vm.game.json.move == 'creator') ||
            (vm.visitor && vm.game.json.move == 'visitor'));
            if(vm.myTurn){
                $('.target').sortable('enable');
                $('#cards-line').sortable('enable');
            } else {
                $('.target').sortable('disable');
                $('#cards-line').sortable('disable');
            }
        }
    }
})();