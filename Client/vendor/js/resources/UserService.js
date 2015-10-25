angular.module('diplomaApp')
    .factory('UserService', ['PATHS', '$resource', function (PATHS, $resource) {
        return $resource(PATHS.SERVER_PATH + "/users");
    }]);