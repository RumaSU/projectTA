function getDate(time = new Date()) {
    return new Date(time);
}

const arrS = [
    {type: 'year', func: 'setFullYear', get: 'getFullYear',}, 
    {type: 'month', func: 'setMonth', get: 'getMonth',}, 
    {type: 'day', func: 'setDate', get: 'getDate',}, 
    {type: 'hours', func: 'setHours', get: 'getHours',}, 
    {type: 'minutes', func: 'setMinutes', get: 'getMinutes',}, 
    {type: 'seconds', func: 'setSeconds', get: 'getSeconds',}, 
    {type: 'milliseconds', func: 'setMilliseconds', get: 'getMilliseconds',},
];
const supportedTimezones = Intl.supportedValuesOf('timeZone');


// year, month, day, hours, minutes, seconds, milliseconds
function getDateLast(from = new Date(), to = 0, t, tz) {
    let now = new Date(from);
    // if (checkTimezoneSupported(tz)) {
    //     console.log('check timezone gagal');
    //     return null;
    // }
    // if (tz) {
    //     console.log('set timezone baru');
    //     now = setDateTimezone(from, tz);
    // }
    
    console.log('now: ', now)

    
    const s = arrS.find(({type}) => type == t);
    if (! s) {
        console.warn(`Invalid type: ${t}`);
        return null;
    }
    
    now[s.func]( now[s.get]() - to );
    
    return now;
    
}

function setDateTimezone(time = new Date(), tz) {
    if (! checkTimezoneSupported(tz)) {
        console.warn(`${tz} is not supported`);
        return null;
    }
    
    const dateF = new Date(time);
    const dateIntlFormat = new Intl.DateTimeFormat('en-US', {timezone: tz}).format(dateF);
    
    return new Date(dateIntlFormat);
}

function checkTimezoneSupported(tz) {
    return supportedTimezones.includes(tz)
}

function checkDateValid(v) {
    const d = new Date(v);
    return d instanceof Date && !isNaN(d);
}

// Set Function
function dateSetFunc(time = new Date(), v, t = 'day') {
    const d = new Date(time);
    
    const s = arrS.find(({type}) => type == t);
    if (! s) {
        console.warn(`Invalid type: ${t}`);
        return null;
    }
    
    d[s.func]( v );
    
    return d;
}

// Get Function
function dateGetFunc(time = new Date(), t = 'day') {
    const d = new Date(time);
    
    const s = arrS.find(({type}) => type == t);
    if (! s) {
        console.warn(`Invalid type: ${t}`);
        return null;
    }
    
    return d[s.get]();
}

