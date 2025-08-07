import { getDocument, GlobalWorkerOptions, TextLayer } from 'pdfjs-dist';
import interact from 'interactjs';
import { comma } from 'postcss/lib/list';

GlobalWorkerOptions.workerSrc = "/public/vendor/pdf.js/build/pdf.worker.mjs" 

const DEFAULT_SCALE = 1.0;
const IDEAL_MAX_CANVAS_WIDTH = 1200;

let pages = [];

let pdf = null;
let total_page = 0;
let current_page = 1;

let canvas = null;
let context = null;
let container = null;
let pageWrapper = null;
let signatureLayer = null;

let scale = DEFAULT_SCALE;

window.is_pdf_load = false;

async function renderPageScroll() {
    
    for(let pageNum = 1; pageNum <= total_page; pageNum++) {
        const page = await pdf.getPage(pageNum);
        pages.push(page);
        
        const pageWrapper = document.createElement("div");
        pageWrapper.classList.add("pdf-page-wrapper", "shadow-md", "shadow-black/40");
        pageWrapper.style.marginBottom = "20px";
        pageWrapper.dataset.pageNumber = pageNum;
        pageWrapper.setAttribute("data-page", pageNum);
        pageWrapper.style.position = "relative";
        
        const viewportOriginal = page.getViewport({ scale: DEFAULT_SCALE });
        const maxWidth = Math.min(window.innerWidth * 0.9, IDEAL_MAX_CANVAS_WIDTH);
        const maxHeight = window.innerHeight * 0.9;
        
        const scaleWidth = maxWidth / viewportOriginal.width;
        const scaleHeight = maxHeight / viewportOriginal.height;
        
        scale = Math.min(scaleWidth, scaleHeight) * window.devicePixelRatio;
        
        const canvas = document.createElement("canvas");
        const context = canvas.getContext("2d");
        
        const viewport = page.getViewport({ scale });
        
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        
        const signatureLayer = document.createElement("div");
        signatureLayer.classList.add("signature-layer");
        signatureLayer.style.position = "absolute";
        signatureLayer.style.top = 0;
        signatureLayer.style.left = 0;
        signatureLayer.style.width = "100%";
        signatureLayer.style.height = "100%";
        signatureLayer.style.zIndex = 10;
        signatureLayer.style.pointerEvents = "auto";
        
        // const textLayerDiv = await addTextLayer(page, viewport);
        
        pageWrapper.appendChild(canvas);
        pageWrapper.appendChild(signatureLayer);
        // pageWrapper.appendChild(textLayerDiv);
        
        container.appendChild(pageWrapper);
         
        await page.render({
            canvasContext: context,
            viewport: viewport
        }).promise;
        
        window.dispatchEvent(new CustomEvent('process_pdf_load', { 
            detail: {
                current_page: pageNum,
                total_page: total_page
            },
            bubbles: true
        }));
    }
    
    is_pdf_load = true;
    window.dispatchEvent(new CustomEvent('status_pdf_load', { 
        detail: {
            status: is_pdf_load
        },
        bubbles: true
    }));
}

async function addTextLayer(page, viewport) {
    const textContent = await page.getTextContent();
    const textLayerDiv = document.createElement("div");
    textLayerDiv.classList.add("textLayer", "no-tailwind-css");
    // textLayerDiv.style.all = "unset";
    textLayerDiv.style.position = 'absolute';
    textLayerDiv.style.top = '0';
    textLayerDiv.style.left = '0';
    textLayerDiv.style.height = `${viewport.height}px`;
    textLayerDiv.style.width = `${viewport.width}px`;
    textLayerDiv.style.zIndex = '2';
    textLayerDiv.style.boxSizing = 'content-box';
    
    const textLayer = new TextLayer({
        textContentSource: textContent, 
        container: textLayerDiv, 
        viewport: viewport
    });
    
    await textLayer.render();
    
    return textLayerDiv;
}

function getCurrentVisiblePage() {
    const pages = document.querySelectorAll(".pdf-page-wrapper");
    let currentVisiblePage = 1;
    let maxVisibleHeight = 0;

    pages.forEach(page => {
        const rect = page.getBoundingClientRect();
        const visibleHeight = Math.min(rect.bottom, window.innerHeight) - Math.max(rect.top, 0);
        
        if (visibleHeight > maxVisibleHeight) {
            maxVisibleHeight = visibleHeight;
            currentVisiblePage = parseInt(page.dataset.pageNumber);
        }
    });

    return currentVisiblePage;
}

window.initPDFViewerScroll = async function(pdfUrl, containerId) {
    const loadingTask = getDocument(pdfUrl);
    pdf = await loadingTask.promise;
    total_page = pdf.numPages;
    
    container = document.getElementById(containerId);
    
    // changePage(1);
    window.dispatchEvent(new CustomEvent('process_pdf_load', { 
        detail: {
            current_page: 0,
            total_page: total_page
        },
        bubbles: true
    }));
    
    await renderPageScroll();
};

window.renderPage = async function (pageNum) {
    
    const page = await pdf.getPage(pageNum);
    pageWrapper.dataset.pageNumber = pageNum;
    pageWrapper.setAttribute("data-page", pageNum);
    
    const viewport = page.getViewport({ scale });

    canvas.width = viewport.width;
    canvas.height = viewport.height;
    
    await page.render({
        canvasContext: context,
        viewport: viewport
    }).promise;
    
    current_page = pageNum;
    
    window.dispatchEvent(new CustomEvent('process_pdf_load', {
        detail: {
            current_page,
            total_page
        },
        bubbles: true
    }));
    
}


window.initPDFViewer = async function(pdfUrl, containerId) {
    const loadingTask = getDocument(pdfUrl);
    pdf = await loadingTask.promise;
    total_page = pdf.numPages;
    
    container = document.getElementById(containerId);
    container.innerHTML = "";
    
    const page = await pdf.getPage(current_page);
    
    const viewportOriginal = page.getViewport({ scale: DEFAULT_SCALE });
    const maxWidth = Math.min(window.innerWidth * 0.9, IDEAL_MAX_CANVAS_WIDTH);
    const maxHeight = window.innerHeight * 0.9;
    
    const scaleWidth = maxWidth / viewportOriginal.width;
    const scaleHeight = maxHeight / viewportOriginal.height;
    scale = Math.min(scaleWidth, scaleHeight) * window.devicePixelRatio;
    
    const viewport = page.getViewport({ scale });
    
    canvas = document.createElement("canvas");
    context = canvas.getContext("2d");
    canvas.width = viewport.width;
    canvas.height = viewport.height;
    
    pageWrapper = document.createElement("div");
    pageWrapper.classList.add("pdf-page-wrapper", "shadow-md", "shadow-black/40");
    pageWrapper.style.marginBottom = "20px";
    pageWrapper.style.position = "relative";
    
    signatureLayer = document.createElement("div");
    signatureLayer.classList.add("signature-layer");
    signatureLayer.style.position = "absolute";
    signatureLayer.style.top = 0;
    signatureLayer.style.left = 0;
    signatureLayer.style.width = "100%";
    signatureLayer.style.height = "100%";
    signatureLayer.style.zIndex = 10;
    signatureLayer.style.pointerEvents = "auto";
    
    
    pageWrapper.appendChild(canvas);
    pageWrapper.appendChild(signatureLayer);
    
    // const textLayerDiv = await addTextLayer(page, viewport);
    // pageWrapper.appendChild(textLayerDiv);
    
    container.appendChild(pageWrapper);
    
    is_pdf_load = true;
    window.dispatchEvent(new CustomEvent('status_pdf_load', { 
        detail: {
            status: is_pdf_load
        },
        bubbles: true
    }));
    
    await renderPage(current_page);
};

// window.initPDFViewer = async function(pdfUrl, canvasId, containerId) {
//     const loadingTask = getDocument(pdfUrl);
//     pdf = await loadingTask.promise;
//     total_page = pdf.numPages;
    
//     canvas = document.getElementById(canvasId);
//     container = document.getElementById(containerId);
    
//     // changePage(1);
//     window.dispatchEvent(new CustomEvent('process_pdf_load', { 
//         detail: {
//             current_page: 0,
//             total_page: total_page
//         },
//         bubbles: true
//     }));
     
//     await renderPageScroll();
// };

window.changePage = async function(page) {
    
    if (typeof page !== "number") {
        page = 1;
    }
    if (current_page === page && is_pdf_load) return;
    
    page = Math.min(page, total_page);
    // renderPage(page);
    renderPageScroll();
};

// window.addEventListener("scroll", () => {
//     if (!window.is_pdf_load) return;

//     const currentPage = getCurrentVisiblePage();

//     // Optional: jika ingin update hanya saat berubah
//     if (window.current_page !== currentPage) {
//         window.current_page = currentPage;

//         window.dispatchEvent(new CustomEvent('visible_pdf_page_change', {
//             detail: { current_page: currentPage },
//             bubbles: true
//         }));
//     }
// });

window.scrollToView = function(pageNum) {
    const target = container.querySelector(`[data-page="${pageNum}"]`);
    if (target) {
        target.scrollIntoView({ behavior: "smooth", block: "start" });
    }
}


window.addSignatureToPage = function(pageNum, xPercent, yPercent, base64Image) {
    // const wrapper = document.querySelector(`.pdf-page-wrapper[data-page-number="${pageNum}"]`);
    // if (!wrapper) return;

    // const signatureLayer = container.querySelector(".signature-layer");
    // if (!signatureLayer) return;
    
    signatureLayer.innerHTML = "";
    
    const img = document.createElement("img");
    img.src = base64Image;
    img.classList.add("signature-item");
    img.style.position = "absolute";
    img.style.left = `${100 * xPercent}%`;
    img.style.top = `${100 * yPercent}%`;
    img.style.transform = "translate(-50%, -50%)";
    img.style.width = "120px";
    img.style.cursor = "move";
    img.style.pointerEvents = "auto";
    img.style.border = "1px";
    img.style.borderColor = "black";
    img.setAttribute("draggable", true);
    
    signatureLayer.appendChild(img);
    
    makeSignatureDraggable(img, signatureLayer);
}

window.makeSignatureDraggable = function (el, onDrop = null) {
    interact(el)
        .draggable({
            inertia: false,
            autoScroll: false,
            modifiers: [
                interact.modifiers.restrictRect({
                    restriction: 'parent',
                    endOnly: true,
                    elementRect: { top: 0, left: 0, bottom: 1, right: 1 }
                })
            ],
            listeners: {
                move(event) {
                    const target = event.currentTarget;
                    const dx = event.dx;
                    const dy = event.dy;
                    
                    let x = ((parseFloat(target.getAttribute('data-x')) || 0) + dx);
                    let y = ((parseFloat(target.getAttribute('data-y')) || 0) + dy);
                    
                    target.style.transform = `translate(${x}px, ${y}px)`;
                    target.setAttribute('data-x', x);
                    target.setAttribute('data-y', y);
                },
                async end(event) {
                    const target = event.currentTarget;
                    
                    // Tangkap posisi transform (hasil drag)
                    const x = parseFloat(target.getAttribute('data-x')) || 0;
                    const y = parseFloat(target.getAttribute('data-y')) || 0;
                    
                    const wrapper = target.closest('.pdf-page-wrapper');
                    const pageNumber = parseInt(wrapper.dataset.pageNumber);
                    
                    const page = await pdf.getPage(current_page);
                    const viewport = page.getViewport({ scale }); // scale harus konsisten
                    
                    const canvasHeight = viewport.height;
                    const canvasWidth = viewport.width;
                    
                    // Posisi dalam container DOM
                    const offsetLeft = target.offsetLeft + x;
                    const offsetTop = target.offsetTop + y;
                    
                    // Ukuran elemen (misal tanda tangan)
                    const widthPx = target.offsetWidth;
                    const heightPx = target.offsetHeight;
                    
                    // Skala ke PDF point
                    const scaleX = 1 / scale;
                    const scaleY = 1 / scale;
                    
                    // Konversi posisi DOM → PDF
                    const pdfX = offsetLeft * scaleX;
                    const pdfY = (canvasHeight - offsetTop - heightPx) * scaleY;
                    const pdfWidth = widthPx * scaleX;
                    const pdfHeight = heightPx * scaleY;
                    console.log({
                        x: pdfX,
                        y: pdfY,
                        width: pdfWidth,
                        height: pdfHeight,
                        pageNum: pageNumber,
                        el: target,
                    });
                    
                    
                    if (onDrop && typeof onDrop === 'function') {
                        const x = parseFloat(target.getAttribute('data-x')) || 0;
                        const y = parseFloat(target.getAttribute('data-y')) || 0;
                        const pageNumber = moved ? parseInt(target.closest('.pdf-page-wrapper').dataset.pageNumber) : parseInt(currentPage.dataset.pageNumber);
                        
                        onDrop({ x, y, el: target, pageNum: pageNumber });
                    }
                    
                }
            }
        });
};

// window.makeSignatureDraggable = function (el, onDrop = null) {
//     interact(el)
//         .draggable({
//             inertia: false,
//             autoScroll: false,
//             modifiers: [
//                 interact.modifiers.restrictRect({
//                     restriction: 'parent',
//                     endOnly: true,
//                     elementRect: { top: 0, left: 0, bottom: 1, right: 1 }
//                 })
//             ],
//             listeners: {
//                 move(event) {
//                     const target = event.currentTarget;
//                     const dx = event.dx;
//                     const dy = event.dy;
                    
//                     let x = ((parseFloat(target.getAttribute('data-x')) || 0) + dx);
//                     let y = ((parseFloat(target.getAttribute('data-y')) || 0) + dy);
                    
//                     target.style.transform = `translate(${x}px, ${y}px)`;
//                     target.setAttribute('data-x', x);
//                     target.setAttribute('data-y', y);
//                 },
//                 end(event) {
                    
//                     const target = event.currentTarget;
//                     const pages = [...document.querySelectorAll('.pdf-page-wrapper')];
//                     const currentPage = target.closest('.pdf-page-wrapper');
                    
//                     if (!currentPage) return;
                    
//                     const currentIndex = pages.indexOf(currentPage);
//                     const rect = currentPage.getBoundingClientRect();
//                     const targetRect = target.getBoundingClientRect();
                    
//                     const elementHeight = targetRect.height;
//                     const threshold = elementHeight * 0.3;
                    
//                     const overflowTop = targetRect.top - rect.top;
//                     const overflowBottom = targetRect.bottom - rect.bottom;
                    
//                     console.log('current index: ', currentIndex);
//                     console.log('overflow top: ', overflowTop);
//                     console.log('overflow bottom', overflowBottom);
                    
//                     let moved = false;
                    
//                     const pdfX = x / scale;
//                     const pdfY = (viewportHeight - y) / scale;
                    
//                     if (overflowTop < -threshold && currentIndex > 0) {
//                         const prevPage = pages[currentIndex - 1];
//                         const prevLayer = prevPage.querySelector('.signature-layer');
                        
//                         prevLayer.appendChild(target); 
                        
//                         const newParentRect = prevLayer.getBoundingClientRect();
//                         const offsetX = targetRect.left - newParentRect.left;
//                         const offsetY = targetRect.top - newParentRect.top;
                        
//                         console.log(offsetX);
//                         console.log(offsetY);
                        
//                         resetTransform(target);
//                         moved = true;
//                     }
                    
//                     else if (overflowBottom > threshold && currentIndex < pages.length - 1) {
//                         const nextPage = pages[currentIndex + 1];
//                         const nextLayer = nextPage.querySelector('.signature-layer');
                        
//                         nextLayer.appendChild(target); 
                        
//                         const newParentRect = nextLayer.getBoundingClientRect();
//                         const offsetX = targetRect.left - newParentRect.left;
//                         const offsetY = targetRect.top - newParentRect.top;
                        
//                         console.log(offsetX);
//                         console.log(offsetY);
                        
//                         resetTransform(target);
//                         moved = true;
//                     }
                    
//                     if (onDrop && typeof onDrop === 'function') {
//                         const x = parseFloat(target.getAttribute('data-x')) || 0;
//                         const y = parseFloat(target.getAttribute('data-y')) || 0;
//                         const pageNumber = moved ? parseInt(target.closest('.pdf-page-wrapper').dataset.pageNumber) : parseInt(currentPage.dataset.pageNumber);
                        
//                         onDrop({ x, y, el: target, pageNum: pageNumber });
//                     }
                    
//                 }
//             }
//         });

//     function resetTransform(target) {
//         target.setAttribute('data-x', 0);
//         target.setAttribute('data-y', 0);
//         target.style.transform = `translate(0px, 0px)`;
//     }
// };



// window.initPDFViewer = async function (pdfUrl, canvasId, containerId) {
//     const loadingTask = getDocument(pdfUrl);
//     const pdf = await loadingTask.promise;
//     const page = await pdf.getPage(1);
//     const scale = 1.5;
//     const viewport = page.getViewport({ scale });
    
    
//     const canvas = document.getElementById(canvasId);
//     const context = canvas.getContext('2d');
//     canvas.width = viewport.width;
//     canvas.height = viewport.height;

//     // Render PDF to canvas
//     await page.render({ canvasContext: context, viewport }).promise;

//     // Set container height to match PDF
//     const container = document.getElementById(containerId);
//     container.style.width = canvas.width + 'px';
//     container.style.height = canvas.height + 'px';
// };

// // Enable dragging of QR elements
// window.initDragQR = function (selector, onDrop = null) {
//     interact(selector)
//         .draggable({
//             modifiers: [
//                 interact.modifiers.restrictRect({
//                     restriction: 'parent',
//                     endOnly: true
//                 })
//             ],
//             listeners: {
//                 move(event) {
//                     const target = event.target;
//                     const x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
//                     const y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

//                     target.style.transform = `translate(${x}px, ${y}px)`;
//                     target.setAttribute('data-x', x);
//                     target.setAttribute('data-y', y);
//                 },
//                 end(event) {
//                     if (onDrop) {
//                         const x = parseFloat(event.target.getAttribute('data-x')) || 0;
//                         const y = parseFloat(event.target.getAttribute('data-y')) || 0;
//                         onDrop({ x, y, el: event.target });
//                     }
//                 }
//             }
//         });
// };
