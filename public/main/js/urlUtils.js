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

/**
 * hierarchy [ '...', '...' ]
 * paramsObj [ {key: '...', value: '...'}, {...} ]
 */
function setHierarchyParam(hierarchy, paramsObj, $isHistoryPushstate = true, $stateObj = {}) {
    const url = new URL(window.location);
    const objHierarchy = hierarchy.map((key) => {
        return {
            key,
            value: whatParamQueryValue(key),
        };
    });
    
    console.log(objHierarchy);
    
    paramsObj.forEach( ({ key, value }) => {
        const findHierarchy = objHierarchy.find(x => x.key == key);
        
        findHierarchy.value = value;
    });
    
    console.log(paramsObj);
    
    const arrParam = objHierarchy
        .filter( ({value}) => value != null && value != undefined && value != '' )
        // .filter( ({value}) => value )
        // .filter( ({key, value}) => key )
        .map( ({key, value}) => ([key, value]) );
    
    console.log(arrParam);
    
    
    const newSearch = arrParam.map(([k, v]) => `${encodeURIComponent(k)}=${encodeURIComponent(v)}`).join('&');
    console.log(newSearch);
    
    if ($isHistoryPushstate) {
        window.history.pushState($stateObj, '', `${url.pathname}?${newSearch}`);
    } else {
        window.history.replaceState($stateObj, '', `${url.pathname}?${newSearch}`);
    }
}

function insertParamAfterKey(keyToInsertAfter, newKey, newValue) {
    const url = new URL(window.location);
    const params = Array.from(url.searchParams.entries()); // [[key, value], ...]

    const newParams = [];
    let inserted = false;

    for (const [key, value] of params) {
        newParams.push([key, value]);

        if (key === keyToInsertAfter && !inserted) {
            newParams.push([newKey, newValue]);
            inserted = true;
        }
    }

    // Jika keyToInsertAfter tidak ditemukan, sisipkan di akhir
    if (!inserted) newParams.push([newKey, newValue]);

    // Build ulang query string
    const newSearch = newParams.map(([k, v]) => `${encodeURIComponent(k)}=${encodeURIComponent(v)}`).join('&');

    // Replace URL tanpa reload
    window.history.replaceState({}, '', `${url.pathname}?${newSearch}`);
}

// $arrObj = [
//     {
//         key: '', 
//         value: ''
//     },
// ]
function setBulkParamsQuery($paramsArrObj, $isHistoryPushstate = true, $stateObj = {}) {
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

function removeParamsQuery($pQuery, $isHistoryPushtate = true, $stateObj = {}) {
    const newUrl = new URL(window.location.href);
    // return new URLSearchParams(window.location.href).set($keyQuery, $valueQuery);
    
    newUrl.searchParams.delete($pQuery);

    if ($isHistoryPushtate) {
        window.history.pushState($stateObj, '', newUrl);
    } else {
        window.history.replaceState($stateObj, '', newUrl);
    }
}


function getWindowLocHref() {
    return window.location.href;
}

function getWindowLocSearch() {
    return window.location.search;
}

function getWindowState() {
    return window.history.state;
}


function whatsNowLocationURL() {
    return window.location;
}
