(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('ActualGameCtrl', ActualGameCtrl);

    ActualGameCtrl.$inject = ['$rootScope', 'NotificationService', 'PATHS', 'game'];

    function ActualGameCtrl($rootScope, NotificationService, PATHS, game) {
        var vm = this;

        vm.refreshPowers = refreshPowers;
        vm.updateField = updateField;
        vm.moveCard = moveCard;

        game.json = JSON.parse(game.json);
        vm.cards = game.creatorId == $rootScope.user.id ? game.json.cards1 : game.json.cards2;

        //Нужно будет убрать и заменить на нормальные карты. vm,realizedCards1 - для себя, vm.realizedCards2 - для противника
        vm.realizedCards1 = game.json.cards1;
        vm.realizedCards2 = game.creatorId == $rootScope.user.id ? game.json.realizedCards1 : game.json.realizedCards2;
        vm.powers = {
            player1: {
                total: 0,
                attack1: 0,
                attack2: 0,
                attack3: 0
            },
            player2: {
                total: 0,
                attack1: 0,
                attack2: 0,
                attack3: 0
            }
        };

        vm.updateField();

        //vm.vm.webSocket = WS.connect(PATHS.SOCKET_PATH);
        //vm.webSocket.on("socket/connect", function (session) {
        //    var channel = 'actual_game/channel';
        //    session.subscribe(channel, function (uri, payload) {
        //    });
        //});


        $(document).ready(function () {
            $('.target').sortable({
                items: '.target:even'
            });

            $('.cards-line').sortable({
                connectWith: '.target',
                revert: true,
                remove: function (event, ui) {
                    ui.item.removeClass('card-blank');
                    ui.item.addClass('realized-card-blank');
                    var cardId = ui.item[0].id.replace('blank', '');
                    vm.moveCard(cardId);
                    vm.updateField();
                    $rootScope.$apply();
                },
                sort: function (event, ui) {
                }
            });
        });

        function refreshPowers() {
            vm.powers = {
                player1: {
                    total: 0,
                    attack1: 0,
                    attack2: 0,
                    attack3: 0
                },
                player2: {
                    total: 0,
                    attack1: 0,
                    attack2: 0,
                    attack3: 0
                }
            };
        }

        function moveCard(cardId){
            for (var i = 0; i < vm.cards.length; i++) {
                if (cardId == vm.cards[i].id) {
                    vm.realizedCards1.push(vm.cards[i]);
                    vm.cards.splice(i, 1);
                }
            }
        }

        function updateField() {
            vm.refreshPowers();
            //noinspection JSDuplicatedDeclaration
            for (var i = 0; i < vm.realizedCards1.length; i++) {
                if (vm.realizedCards1[i].attackType == 1) {
                    vm.powers.player1.attack1 += vm.realizedCards1[i].power;
                }
                if (vm.realizedCards1[i].attackType == 2) {
                    vm.powers.player1.attack2 += vm.realizedCards1[i].power;
                }
                if (vm.realizedCards1[i].attackType == 3) {
                    vm.powers.player1.attack3 += vm.realizedCards1[i].power;
                }
            }
            //noinspection JSDuplicatedDeclaration
            for (var i = 0; i < vm.realizedCards2.length; i++) {
                if (vm.realizedCards2[i].attackType == 1) {
                    vm.powers.player2.attack1 += vm.realizedCards2[i].power;
                }
                if (vm.realizedCards2[i].attackType == 2) {
                    vm.powers.player2.attack2 += vm.realizedCards2[i].power;
                }
                if (vm.realizedCards2[i].attackType = 3) {
                    vm.powers.player2.attack3 += vm.realizedCards2[i].power;
                }
            }
            vm.powers.player1.total = vm.powers.player1.attack1 + vm.powers.player1.attack2 + vm.powers.player1.attack3;
            vm.powers.player2.total = vm.powers.player2.attack1 + vm.powers.player2.attack2 + vm.powers.player2.attack3;
        }
    }
})();