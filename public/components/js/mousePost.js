function getMousePosition(evt) {
    // const x = evt.pageX || (evt.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft));
    // const y = evt.pageY || (evt.clientY + (document.documentElement.scrollTop || document.body.scrollTop));
    // return { x, y };
    const x = evt.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft);
    const y = evt.clientY + (document.documentElement.scrollTop || document.body.scrollTop);
    return { x, y };
}