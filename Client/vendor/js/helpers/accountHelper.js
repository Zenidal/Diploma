function isUser($location, $rootScope, $q) {
    var deferred = $q.defer();
    if (!($rootScope.user && $rootScope.user.isAuthorized)) {
        $location.path("/home");
        deferred.reject();
    } else {
        deferred.resolve();
    }
    return deferred.promise;
}

function isActualGameExist($http, NotificationService, PATHS) {
    return $http({
        method: 'GET',
        url: PATHS.SERVER_PATH + '/checkGame'
    }).then(function successCallback(response) {
        return response.data.isExist ? response.data.isExist : null;
    }, function errorCallback(response) {
        NotificationService.addErrorMessage(response.data.errorMessage);
        return null;
    });
}