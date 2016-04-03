(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('ActualGameCtrl', ActualGameCtrl);

    ActualGameCtrl.$inject = ['$rootScope', 'PATHS', 'actualGame'];

    function ActualGameCtrl($rootScope, PATHS, actualGame) {
        var vm = this;
        vm.game = actualGame;
        vm.isUser1 = false;
        vm.isUser2 = false;
        vm.myTurn = false;
        vm.iPassed = false;
        vm.opponentPassed = false;
        vm.resultMessage = '';

        vm.init = init;
        vm.moveCard = moveCard;
        vm.updateField = updateField;
        vm.pass = pass;

        vm.init();
        vm.updateField();

        //functions
        function init() {
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

        function pass() {
            var webSocket = WS.connect(PATHS.SOCKET_PATH);
            webSocket.on("socket/connect", function (session) {
                session.publish(vm.socketChannel, {
                    userId: $rootScope.user.id,
                    gameId: vm.game.id,
                    event: 'pass'
                });
            });
        }

        function updateField() {
            vm.isUser1 = vm.game.json.user1.userId == $rootScope.user.id;
            vm.isUser2 = vm.game.json.user2.userId == $rootScope.user.id;
            vm.myTurn = (vm.game.json.move == 'user1' && vm.isUser1) ||
                (vm.game.json.move == 'user2' && vm.isUser2);
            if ((vm.isUser1 && vm.game.json.user1.passed) ||
                (vm.isUser2 && vm.game.json.user2.passed)) {
                vm.iPassed = true;
            } else if (vm.myTurn) {
                vm.iPassed = false;
            }
            vm.opponentPassed = (vm.isUser1 && vm.game.json.user2.passed) ||
                (vm.isUser2 && vm.game.json.user1.passed);
            if (vm.myTurn && !vm.iPassed) {
                $('.target').sortable('enable');
                $('#cards-line').sortable('enable');
            } else {
                $('.target').sortable('disable');
                $('#cards-line').sortable('disable');
            }
            if (vm.game.json.winner) {
                var gameResult = vm.game.json.winner;
                if (vm.isUser1 && gameResult == 'user1' ||
                    vm.isUser2 && gameResult == 'user2') {
                    vm.resultMessage = 'I won';
                } else if (vm.isUser1 && gameResult == 'user2' ||
                    vm.isUser2 && gameResult == 'user1') {
                    vm.resultMessage = 'Opponent won';
                } else {
                    vm.resultMessage = 'Draw';
                }
            }
            if (vm.resultMessage) {
                $('.target').sortable('disable');
                $('#cards-line').sortable('disable');
            }
        }
    }
})();