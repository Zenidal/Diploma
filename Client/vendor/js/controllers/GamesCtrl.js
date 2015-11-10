(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('GamesCtrl', GamesCtrl);

    GamesCtrl.$inject = ['NotificationService', 'GameService', 'PATHS', 'games'];

    function GamesCtrl(NotificationService, GameService, PATHS, games) {
        var vm = this;

        var myClank = Clank.connect(PATHS.SOCKET_PATH + '/game');
        myClank.on("socket/connect", function(session){
            //session is an Autobahn JS WAMP session.

            console.log("Successfully Connected!");
        });
        myClank.on("socket/disconnect", function(error){
            //error provides us with some insight into the disconnection: error.reason and error.code

            console.log("Disconnected for " + error.reason + " with code " + error.code);
        });

        vm.createdGames = games;
        vm.createGame = createGame;
        vm.acceptGame = acceptGame;

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