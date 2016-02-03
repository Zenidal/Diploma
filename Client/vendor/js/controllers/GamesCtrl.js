(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('GamesCtrl', GamesCtrl);

    GamesCtrl.$inject = ['NotificationService', 'GameService', 'PATHS', 'games', '$scope'];

    function GamesCtrl(NotificationService, GameService, PATHS, games, $rootScope) {
        var vm = this;

        vm.createdGames = games;
        vm.createGame = createGame;
        vm.acceptGame = acceptGame;

        vm.webSocket = WS.connect(PATHS.SOCKET_PATH);
        vm.webSocket.on("socket/connect", function (session) {
            session.subscribe('app/channel', function (uri, payload) {
                if (payload.games) {
                    vm.createdGames = [];
                    for(var i = 0; i < payload.games.length; i++) {
                        if (payload.games[i].creator.id != $rootScope.user.id) {
                            vm.createdGames.push(payload.games[i]);
                        }
                    }
                    $rootScope.$apply();
                }
            });
        });
        vm.webSocket.on("socket/disconnect", function (error) {
            console.log("Disconnected for " + error.reason + " with code " + error.code);
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
            GameService.acceptGame(id);
        }
    }
})();