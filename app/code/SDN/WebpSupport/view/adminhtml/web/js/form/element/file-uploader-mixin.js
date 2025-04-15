define([], function () {
    'use strict';

    return function (FileUploader) {
        return FileUploader.extend({
            /**
             * Override method to allow .webp
             */
            isFileExtensionAllowed: function (fileName) {
                var allowedExtensions = this.allowedExtensions || ['jpg', 'jpeg', 'gif', 'png', 'webp'];
                var extension = fileName.split('.').pop().toLowerCase();
                return allowedExtensions.indexOf(extension) !== -1;
            },
            isFileTypeAllowed: function (file) {
                var allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/gif',
                    'image/webp'
                ];
                return allowedTypes.indexOf(file.type) !== -1;
            }
        });
    };
});
