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
        vm.createGame = createGame;
        vm.acceptGame = acceptGame;

        vm.webSocket = WS.connect(PATHS.SOCKET_PATH);
        vm.webSocket.on("socket/connect", function (session) {
            session.subscribe('app/channel', function (uri, payload) {
                if (payload.games) {
                    vm.createdGames = [];
                    for(var i = 0; i < payload.games.length; i++) {
                        if (payload.games[i].creator && payload.games[i].creator.id != $rootScope.user.id) {
                            vm.createdGames.push(payload.games[i]);
                        } else if (payload.games[i].creator && payload.games[i].creator.id == $rootScope.user.id) {
                            vm.myGame = payload.games[i];
                            if(vm.myGame.visitor && vm.myGame.visitor.username){
                                $location.path("/actual_game");
                                vm.webSocket.off();
                            }
                        }
                    }
                    vm.gamesLoaded = true;
                    $rootScope.$apply();
                }
            });
        });
        vm.webSocket.on("socket/disconnect", function (error) {
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
                    vm.webSocket.off();
                }
            }, function errorCallback(response) {
                NotificationService.addErrorMessage(response.data.errorMessage);
            });
            $('#acceptGameModal').modal('hide');
        }
    }
})();