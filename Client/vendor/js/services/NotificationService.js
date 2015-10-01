angular.module('diplomaApp')
    .factory('NotificationService', [function () {
        var messages = [];
        var errors = [];

        return {
            addErrorMessage: function (errorMessage) {
                errors.push(errorMessage);
            },
            addMessage: function (message) {
                messages.push(message);
            },
            messages: messages,
            errors: errors
        };
    }]);