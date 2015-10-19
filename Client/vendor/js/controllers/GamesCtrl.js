(function () {
    'use strict';

    angular.module('diplomaControllers')
        .controller('GamesCtrl', GamesCtrl);

    GamesCtrl.$inject = [];

    function GamesCtrl() {
        var vm = this;
        vm.createdGames = [
            {
                id: 'Game 1',
                creatorUsername: 'Zenidal'
            },
            {
                id: 'Game 2',
                creatorUsername: 'Zenidal'
            },
            {
                id: 'Game 3',
                creatorUsername: 'Zenidal'
            },
            {
                id: 'Game 4',
                creatorUsername: 'Zenidal'
            },
            {
                id: 'Game 5',
                creatorUsername: 'Zenidal'
            },
            {
                id: 'Game 6',
                creatorUsername: 'Zenidal'
            },
            {
                id: 'Game 7',
                creatorUsername: 'Zenidal'
            },
            {
                id: 'Game 8',
                creatorUsername: 'Zenidal'
            },
            {
                id: 'Game 9',
                creatorUsername: 'Zenidal'
            },
            {
                id: 'Game 10',
                creatorUsername: 'Zenidal'
            },
            {
                id: 'Game 11',
                creatorUsername: 'Zenidal'
            },
            {
                id: 'Game 12',
                creatorUsername: 'Zenidal'
            },
            {
                id: 'Game 13',
                creatorUsername: 'Zenidal'
            },
            {
                id: 'Game 14',
                creatorUsername: 'Zenidal'
            },
            {
                id: 'Game 15',
                creatorUsername: 'Zenidal'
            }
        ]
    }
})();