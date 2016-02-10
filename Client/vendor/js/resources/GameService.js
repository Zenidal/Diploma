angular.module('diplomaApp')
    .factory('GameService', ['PATHS', 'NotificationService', '$resource', '$http', '$location', function (PATHS, NotificationService, $resource, $http, $location) {
        return {
            resource: $resource(PATHS.SERVER_PATH + "/games")
        };
    }]);