(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('GamesCtrl', GamesCtrl);

    GamesCtrl.$inject = ['NotificationService', 'GameService', 'PATHS', '$scope', '$location', '$http'];

    function GamesCtrl(NotificationService, GameService, PATHS, $rootScope, $location, $http) {
        var vm = this;

        vm.gamesLoaded = false;
        vm.createdGames = [];
        vm.myGame = null;
        vm.channel = 'app/channel';
        vm.createGame = createGame;
        vm.acceptGame = acceptGame;

        vm.webSocket = WS.connect(PATHS.SOCKET_PATH);
        vm.webSocket.on("socket/connect", function (session) {
            session.subscribe(vm.channel, function (uri, payload) {
                if (!$rootScope.user) {
                    session.unsubscribe(vm.channel);
                }
                if (payload.games) {
                    vm.createdGames = [];
                    for (var i = 0; i < payload.games.length; i++) {
                        if ($rootScope.user) {
                            if (
                                payload.games[i].creator
                                && payload.games[i].creator.id != $rootScope.user.id
                            ) {
                                vm.createdGames.push(payload.games[i]);
                            } else {
                                vm.myGame = payload.games[i];
                            }
                            if (
                                (
                                    payload.games[i].creator
                                    && payload.games[i].creator.id
                                    && payload.games[i].visitor
                                    && payload.games[i].visitor.id
                                    && payload.games[i].creator.id == $rootScope.user.id
                                )
                                || (
                                    payload.games[i].visitor
                                    && payload.games[i].visitor.id
                                    && payload.games[i].creator
                                    && payload.games[i].creator.id
                                    && payload.games[i].visitor.id == $rootScope.user.id
                                )
                            ) {
                                session.unsubscribe(vm.channel);
                                $location.path("/actual_game");
                                $location.replace();
                            }
                        }
                    }
                    vm.gamesLoaded = true;
                    $rootScope.$apply();
                }
            });
        });
        vm.webSocket.on("socket/disconnect", function (error) {
            NotificationService.addErrorMessage('WebSocket Error: ' + error.reason + ' Try later');
        });

        function createGame() {
            var Game = new GameService.resource;
            Game.gameName = vm.game && vm.game.name ? vm.game.name : null;
            Game.$save(
                function (response) {
                    if (response.errorMessage !== undefined && response.errorMessage !== null) {
                        NotificationService.addErrorMessage(response.errorMessage);
                    }
                    if (response.message !== undefined && response.message !== null) {
                        NotificationService.addMessage(response.message);
                    }
                },
                function (response) {
                    NotificationService.addErrorMessage(response.errorMessage);
                }
            );
        }

        function acceptGame(id) {
            $http({
                method: 'POST',
                url: PATHS.SERVER_PATH + '/games/' + id + '/accept'
            }).then(function successCallback(response) {
                if (response.data.errorMessage !== undefined && response.data.errorMessage !== null) {
                    NotificationService.addErrorMessage(response.data.errorMessage);
                }
                if (response.data.message !== undefined && response.data.message !== null) {
                    NotificationService.addMessage(response.data.message);
                    $location.path("/actual_game");
                    $location.replace();
                }
            }, function errorCallback(response) {
                NotificationService.addErrorMessage(response.data.errorMessage);
            });
            $('#acceptGameModal').modal('hide');
        }
    }
})();