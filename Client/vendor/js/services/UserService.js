angular.module('diplomaApp')
    .factory('UserService', ['$rootScope', '$resource', function ($rootScope, $resource) {
        return $resource($rootScope.serverPath + "/users");
    }]);