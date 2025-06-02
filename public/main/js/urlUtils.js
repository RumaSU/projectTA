function whatParamQueryValue($pQuery) {
    return new URLSearchParams(window.location.search).get($pQuery);
}

function isParamQueryExists($pQuery) {
    return new URLSearchParams(window.location.search).has($pQuery);
}

function setParamsQuery($keyQuery, $valueQuery) {
    const newUrl = new URL(window.location.href);
    // return new URLSearchParams(window.location.href).set($keyQuery, $valueQuery);
    
    newUrl.searchParams.set($keyQuery, $valueQuery);
    window.history.pushState({}, '', newUrl);
}

function removeParamsQuery($pQuery) {
    const newUrl = new URL(window.location.href);
    // return new URLSearchParams(window.location.href).set($keyQuery, $valueQuery);
    
    newUrl.searchParams.delete($pQuery);
    window.history.pushState({}, '', newUrl);
}


function getWindowLocHref() {
    return window.location.href;
}

function getWindowLocSearch() {
    return window.location.search;
}