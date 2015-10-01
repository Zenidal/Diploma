angular.module('diplomaApp')
    .factory('AuthInterceptorService', ['$q', '$injector', function ($q, $injector) {
        var authInterceptorServiceFactory = {};
        var _request = function (config) {

            config.headers = config.headers || {};

            var appUserService = $injector.get('AppUserService');
            if (appUserService.user.isAuthorized) {
                config.headers['user-token'] = appUserService.user.apiKey;
            }
            return config;
        };
        authInterceptorServiceFactory.request = _request;
        return authInterceptorServiceFactory;
    }]);