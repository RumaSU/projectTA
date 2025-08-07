console.log("resources events documents, process new documents");



window.Echo.private(`now-process_new_docs.${window.Laravel.sessionId}`)
    .listen('.Now_ProcessNewDocs', ($data) => {
        console.log("now process new docs");
        console.log("data: ", $data);
    });


window.Echo.private("now-process_new_docs")
    .listen('.Now_ProcessNewDocs', ($data) => {
        console.log("now process new docs with user id");
        console.log("data: ", $data);
    });