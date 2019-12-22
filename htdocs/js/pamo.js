/**
 * To show off JS works and can be integrated.
 */
"use strict";

// eslint-disable-next-line no-unused-vars
function tableLinks(id, url) {
    var element = document.getElementById(id);

    element.addEventListener("click", function() {
        location.href = url;
    });
}

function reloadHere(id, url) {
    var element = document.getElementById(id);

    element.addEventListener("click", function() {
        if (history.pushState) {
            window.history.pushState({path:url},'',url);
        }
        document.location.reload(true);
    });
}

function goBack(id) {
    var element = document.getElementById(id);

    element.addEventListener("click", function() {
        window.history.back();
    });
}
