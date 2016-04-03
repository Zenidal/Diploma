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