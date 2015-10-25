angular.module('diplomaApp')
    .factory('GameService', ['PATHS', 'NotificationService', '$resource', '$http', function (PATHS, NotificationService, $resource, $http) {
        var acceptGame = function (id) {
            $http({
                method: 'POST',
                url: PATHS.SERVER_PATH + '/games/' + id + '/accept'
            }).then(function successCallback(response) {
                if (response.data.errorMessage !== undefined && response.data.errorMessage !== null) {
                    NotificationService.addErrorMessage(response.data.errorMessage);
                }
                if (response.data.message !== undefined && response.data.message !== null) {
                    NotificationService.addMessage(response.data.message);
                }
            }, function errorCallback(response) {
                NotificationService.addErrorMessage(response.data.errorMessage);
            });
        };

        return {
            resource: $resource(PATHS.SERVER_PATH + "/games"),
            acceptGame: acceptGame
        };
    }]);