(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('GamesCtrl', GamesCtrl);

    GamesCtrl.$inject = ['NotificationService', 'GameService', 'PATHS', 'games'];

    function GamesCtrl(NotificationService, GameService, PATHS, games) {
        var vm = this;

        vm.createdGames = games;
        vm.createGame = createGame;
        vm.acceptGame = acceptGame;
        vm.wsConnection = new WebSocket('ws://localhost:333');

        vm.wsConnection.onopen = function (event) {
            console.log("Connection established!");
        };

        vm.wsConnection.onerror = function(event) {
            console.log("The connection could not be established due to an error.");
        };

        vm.wsConnection.onmessage = function (event) {
            console.log(event.data);
        };

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