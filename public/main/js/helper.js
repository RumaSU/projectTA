function randomString(length = 8, lower = true, number = true) {
    if (!length) return null;
    
    let characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    if (lower) characters += "abcdefghijklmnopqrstuvwxyz";
    if (number) characters += "0123456789";
    
    let result = '';
    
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    
    return result;
}

function formatBytes(bytes, decimals = 2, binary = false) {
    if (bytes === 0) return '0 Bytes';
    
    const k = binary ? 1024 : 1000;
    const sizes = binary
        ? ['Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB']
        : ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    const dm = decimals < 0 ? 0 : decimals;
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

function generateClientToken(length = 24) {
    const array = new Uint8Array(length);
    crypto.getRandomValues(array);
    
    return Array.from(array, dec => dec.toString(16).padStart(2, '0')).join('').slice(0, length);
}