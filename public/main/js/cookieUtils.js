function setNewCookie(key, value, path = '/', expire = 60*60*24*30) {
    document.cookie = `${key}=${value}; path=${path}; max-age=${expire};`;
}