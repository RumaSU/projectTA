

window.Echo.private(`now-status_upload.${window.Laravel.sessionId}`)
    .listen('.Now_ProcessStatusUpload', ($data) => {
        console.log("now process status upload");
        console.log("data: ", $data);
    });