angular.module('diplomaApp')
    .factory('AppUserService', ['$http',
        function AppUserService($http) {
            var user = {
                username: '',
                roleName: '',
                apiKey: '',
                id: 0,
                isAuthorized: false
            };

            return {
                addUser: function (username, roleName, apiKey, id) {
                    user.isAuthorized = true;
                    user.username = username;
                    user.roleName = roleName;
                    user.apiKey = apiKey;
                    user.id = id;
                },
                removeUser: function () {
                    user.isAuthorized = false;
                    user.username = '';
                    user.roleName = '';
                    user.apiKey = '';
                    user.id = 0;
                },
                user: user
            };
        }]);