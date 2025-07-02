function getAllCookies() {
    return document.cookie;
}

// function setNewCookie(key, value, path = '/', expire = 60*60*24*30, sameSite='Lax') {
function setNewCookie(key, value, path = '/', expire = 60*60*24*30, sameSite='Lax') {
    // document.cookie = `${key}=${value}; path=${path}; max-age=${expire};`;
    const encodedKey = encodeURIComponent(key);
    const encodedValue = encodeURIComponent(value);
    let expiredValue = expire == 'delete' ? new Date(0) : expire;
    // document.cookie = `${encodedKey}=${encodedValue}; path=${path}; max-age=${expiredValue}; SameSite=${sameSite}`;
    document.cookie = `${encodedKey}=${encodedValue}; path=${path}; max-age=${expiredValue}`;
}

// string(just value), object(return as object {key: value})
function getCookie(name, returnType = 'object') {
    const nameCookie = name.endsWith('=') ? name : `${name}=`;
    const nameRegex = new RegExp(`(${nameCookie})`);
    const decodeCookie = decodeURIComponent(getAllCookies());
    const arrCookies = decodeCookie.split(';');
    
    const rawCookie = arrCookies.find(cookie => cookie.trim().startsWith(nameCookie));
    
    if (! rawCookie) {
        // console.warn(`Cookie ${name} is not found`);
        return null;
    }
    
    const value = rawCookie.trim().slice(nameCookie.length);
    
    return returnType === 'string'
        ? value
        : { key: name, value };
}

function checkCookie(name) {
    const nameCookie = name.endsWith('=') ? name : `${name}=`;
    
    const decodeCookie = decodeURIComponent(getAllCookies());
    const arrCookies = decodeCookie.split(';');
    
    return arrCookies.some(cookie => cookie.trim().startsWith(nameCookie));
}

function deleteCookie(name) {
    const nameCookie = name.endsWith('=') ? name : `${name}=`;
    document.cookie = `${nameCookie}; expires=${new Date(0)}; path=/;`;
}