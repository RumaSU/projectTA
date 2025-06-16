function whatParamQueryValue($pQuery) {
    return new URLSearchParams(window.location.search).get($pQuery);
}

function isParamQueryExists($pQuery) {
    return new URLSearchParams(window.location.search).has($pQuery);
}

function setParamsQuery($keyQuery, $valueQuery, $isHistoryPushstate = true, $stateObj = {}) {
    if (!$valueQuery) {
        removeParamsQuery($keyQuery);
        return;
    }
    
    const newUrl = new URL(window.location.href);
    // return new URLSearchParams(window.location.href).set($keyQuery, $valueQuery);
    
    newUrl.searchParams.set($keyQuery, $valueQuery);
    
    if ($isHistoryPushstate) {
        window.history.pushState($stateObj, '', newUrl);
    } else {
        window.history.replaceState($stateObj, '', newUrl);
    }
}

// $arrObj = [
//     {
//         key: '', 
//         value: ''
//     },
// ]
function setBulkParamsQuery($paramsArrObj, $isHistoryPushstate = true, $stateObj) {
    const newUrl = new URL(window.location.href);

    $paramsArrObj.forEach((x) => {
        newUrl.searchParams.set(x.key, x.value);
    });

    if ($isHistoryPushstate) {
        window.history.pushState($stateObj, '', newUrl);
    } else {
        window.history.replaceState($stateObj, '', newUrl);
    }
}

function removeParamsQuery($pQuery, $isHistoryPushtate = true) {
    const newUrl = new URL(window.location.href);
    // return new URLSearchParams(window.location.href).set($keyQuery, $valueQuery);
    
    newUrl.searchParams.delete($pQuery);

    if ($isHistoryPushtate) {
        window.history.pushState({}, '', newUrl);
    }
}


function getWindowLocHref() {
    return window.location.href;
}

function getWindowLocSearch() {
    return window.location.search;
}