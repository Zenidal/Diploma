angular.module('diplomaApp')
    .factory('ResponseObserverService', function responseObserver($q, $location, $injector) {
        return {
            'responseError': function (errorResponse) {
                switch (errorResponse.status) {
                    case 403:
                        var appUserService = $injector.get('AppUserService');
                        appUserService.removeUser();
                        $location.path('#/home');
                        break;
                }
                return $q.reject(errorResponse);
            }
        };
    });