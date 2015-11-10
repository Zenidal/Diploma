angular.module('diplomaApp')
    .factory('AppUserService', ['$http', '$rootScope', function ($http, $rootScope) {
        var user = {
            username: '',
            roleName: '',
            apiKey: '',
            id: 0,
            isAuthorized: false
        };

        return {
            addUser: function (username, roleName, apiKey, id) {
                if (!user || !user.isAuthorized) {
                    user.isAuthorized = true;
                    user.username = username;
                    user.roleName = roleName;
                    user.apiKey = apiKey;
                    user.id = id;
                    if (!localStorage['user.apiKey']) {
                        localStorage['user.username'] = user.username;
                        localStorage['user.roleName'] = user.roleName;
                        localStorage['user.apiKey'] = user.apiKey;
                        localStorage['user.id'] = user.id;
                    }
                    $rootScope.user = user;
                }
            },
            removeUser: function () {
                if (user.isAuthorized) {
                    user.isAuthorized = false;
                    user.username = '';
                    user.roleName = '';
                    user.apiKey = '';
                    user.id = 0;
                    if (localStorage['user.apiKey']) {
                        localStorage.removeItem('user.username');
                        localStorage.removeItem('user.roleName');
                        localStorage.removeItem('user.apiKey');
                        localStorage.removeItem('user.id');
                    }
                    if ($rootScope.user) {
                        delete $rootScope.user;
                    }
                }
            },
            user: user
        };
    }]);