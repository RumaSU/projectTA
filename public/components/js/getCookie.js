function getCookie(cookieName) {
    const dCookies = document.cookie.split(';');
    for (let iCookie of dCookies) {
        const [name, value] = iCookie.split('=');
        if (name === cookieName) {
            return decodeURIComponent(value); // Decode nilai cookie (jika diencode)
        }
    }
    return null;
}