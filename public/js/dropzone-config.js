// Initialise DropZone form control
Dropzone.options.realDropzone = {
    maxFilesize: 20, // Mb
    init: function () {
        // Set up any event handlers
        this.on('complete', function () {
            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                location.reload();
            }
        });
    }
};
