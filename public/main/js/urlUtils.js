// ---------------------------
// 🔧 URL & State Helpers
// ---------------------------

function getLocation() {
    return window.location;
}

function getLocationHref() {
    return window.location.href;
}

function getLocationSearch() {
    return window.location.search;
}

function getURL() {
    return new URL(window.location.href);
}

function getURLSearch() {
    return new URLSearchParams(window.location.search);
}

function getStateObject() {
    return window.history.state;
}

function windowPustate($stateObj = getStateObject(), $unused = '', $url) {
    if ($stateObj === null) {
        $stateObj = getStateObject();
    }
    window.history.pushState($stateObj, $unused, $url);
}

function windowReplacestate($stateObj = getStateObject(), $unused = '', $url) {
    if ($stateObj === null) {
        $stateObj = getStateObject();
    }
    
    window.history.replaceState($stateObj, $unused, $url);
}

function updateURLParams(modifierCallback) {
    const url = getURL();
    modifierCallback(url.searchParams);
    return url.toString();
}


// ---------------------------
// 🔍 Parameter Functions
// ---------------------------

function paramValue($key) {
    const url = getURLSearch();
    
    return $key ? 
        url.get($key) :
        // Array.from( url.entries() ).map( x => x.reduce( (key, value, index) => { return { key, value } } ) );
        Array.from( url.entries() ).map( ([key, value]) => ({key, value}) );
        // Array.from( url.entries() );
}

function paramValueAllKey($key) {
    return new URLSearchParams(window.location.search).getAll($key);
}

function paramValueAllPrefix($prefix) {
    if (!$prefix) return null;
    
    const entries = paramValue();
    const matched = [];
    
    for (const [key, value] of entries) {
        if (key.startsWith('filter[')) {
            const innerKey = key.match(/^filter\[(.+)\]$/)?.[1];
            if (innerKey) matched.push({key, value});
        }
    }
    
    return {
        prefix: $prefix,
        value: matched,
        length: matched.length,
    };
}

function paramExists($key) {
    return getURLSearch().has($key);
}

function paramSet($key, $value, $isPushtate = true, $stateObj = getStateObject()) {
    if (!$value) {
        return paramRemove($key, $isPushtate, $stateObj);
    }
    // const url = getURL();
    // const newUrl = url.searchParams.set($key, $value);
    const newUrl = updateURLParams(params => params.set($key, $value));
    
    return $isPushtate ?
        windowPustate($stateObj, '', newUrl) :
        windowReplacestate($stateObj, '', newUrl);
}

function paramSetBulk($params, $isPushtate = true, $stateObj = getStateObject()) {
    if (!Array.isArray($params) ||
        !$params.every(obj => 'key' in obj && 'value' in obj)) {
        return;
    }
    
    const location = getLocation();
    const filtered = $params.filter(({ value }) => !!value);
    const queryString = filtered
        .map( ({ key, value }) => `${encodeURIComponent(key)}=${encodeURIComponent(value)}` )
        .join('&');

    const newUrl = `${location.pathname}${queryString ? '?' + queryString : ''}`;
    
    return $isPushtate ?
        windowPustate($stateObj, '', newUrl) :
        windowReplacestate($stateObj, '', newUrl);
}

function paramSetHierarchy($hierarchy, $params, $isPushtate = true, $stateObj = getStateObject()) {
    if (!Array.isArray($params) ||
        !$params.every(obj => 'key' in obj && 'value' in obj)) {
        return;
    }
    
    const hierarchySet = $hierarchy.map((key) => {
        return {
            key,
            value: paramValue(key),
        }
    });
    
    for (const {key, value} of $params) {
        const f = hierarchySet.find(x => x.key == key);
        f.value = value
    }
    
    const location = getLocation();
    const filtered = hierarchySet.filter(({ value }) => !!value)
    const queryString = filtered
        .map( ({ key, value }) => `${encodeURIComponent(key)}=${encodeURIComponent(value)}` )
        .join('&');
    const newUrl = `${location.pathname}${queryString ? '?' + queryString : ''}`;
    
    return $isPushtate ?
        windowPustate($stateObj, '', newUrl) :
        windowReplacestate($stateObj, '', newUrl);
    
}

function paramRemove($key, $isPushtate = true, $stateObj = getStateObject()) {
    // const url = getURL();
    // const newURL = url.searchParams.delete($key);
    const newURL = updateURLParams(params => params.delete($key));
    
    return $isPushtate ?
        windowPustate($stateObj, '', newURL) :
        windowReplacestate($stateObj, '', newURL);
}

function paramRemoveBulk($keys, $isPushtate = true, $stateObj = getStateObject()) {
    if (! $keys.length) return;
    
    let params = paramValue();
    
    for (const key of $keys) {
        params = params.filter(x => x.key !== key);
    }
    
    return paramSetBulk(params, $isPushtate, $stateObj);
}














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
    
    paramsObj.forEach( ({ key, value }) => {
        const findHierarchy = objHierarchy.find(x => x.key == key);
        
        findHierarchy.value = value;
    });
    
    const arrParam = objHierarchy
        .filter( ({value}) => value != null && value != undefined && value != '' )
        // .filter( ({value}) => value )
        // .filter( ({key, value}) => key )
        .map( ({key, value}) => ([key, value]) );
    
    
    const newSearch = arrParam.map(([k, v]) => `${encodeURIComponent(k)}=${encodeURIComponent(v)}`).join('&');
    
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
