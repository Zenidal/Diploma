function isUser($location, $rootScope) {
    if (!($rootScope.user && $rootScope.user.isAuthorized)) {
        $location.path("/home");
    }
}