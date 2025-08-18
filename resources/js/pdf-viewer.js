import { getDocument, GlobalWorkerOptions, TextLayer } from 'pdfjs-dist';
import interact from 'interactjs';

GlobalWorkerOptions.workerSrc = '/build/pdf.worker.mjs';
const DEFAULT_SCALE = 1.0;
const IDEAL_MAX_CANVAS_WIDTH = 1400;

const DEFAULT_QR_HEIGHT = 250;
const DEFAULT_QR_WIDTH = 250;

const PX_TO_MM = 0.2645833333;
const MM_TO_PX = 3.7795275591;
const PDFJS_DPI = 96 / 72;

let pdf = null;
let total_page = 0;
let current_page = 1;

let canvas = null;
let context = null;
let container = null;
let pageWrapper = null;
let signatureLayer = null;

let displayScale = 1.0;
let pdfScaleFactor = 1.0;
let pdfScale = DEFAULT_SCALE;
let qrScale = 1;
let scale = DEFAULT_SCALE;

window.is_pdf_load = false;

let renderTask = null;

let loadingRender = false;
let queueRender = 0;

let resultMove = null;

let imgQR = null;


window.renderPage = async function (pageNum) {
    
    if (! pdf) {
        console.error("PDF not loaded yet.");
        return;
    }
    
    if (typeof pageNum !== "number" || pageNum < 1) {
        pageNum = 1;
    } else if (pageNum > total_page) {
        pageNum = total_page;
    }
    
    if (loadingRender) {
        queueRender = pageNum;
        return;
    }
    
    loadingRender = true;
    
    try {
        const page = await pdf.getPage(pageNum);
        pageWrapper.dataset.pageNumber = pageNum;
        pageWrapper.setAttribute("data-page", pageNum);
        
        const viewport = page.getViewport({ scale: pdfScale });
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        
        renderTask = page.render({
            canvasContext: context,
            viewport: viewport
        });
        
        await renderTask.promise;
        
        current_page = pageNum;
        window.dispatchEvent(new CustomEvent('process_pdf_load', {
            detail: { current_page, total_page, pdfScale },
            bubbles: true
        }));
        
        if (imgQR instanceof Element) {
            updateSignatureData(imgQR);
        }
        
        
    } catch (err) {
        
        if (err?.name !== "RenderingCancelledException") {
            console.error("Render error:", err);
        }
        
    } finally {
        loadingRender = false;
        
        if (queueRender) {
            const nextPage = queueRender;
            queueRender = 0;
            renderPage(nextPage);
        }
    }
    
}


window.initPDFViewer = async function(pdfUrl, containerId) {
    const loadingTask = getDocument(pdfUrl);
    
    pdf = await loadingTask.promise;
    total_page = pdf.numPages;
    
    container = document.getElementById(containerId);
    container.innerHTML = "";
    
    const page = (await pdf.getPage(current_page)) || (await pdf.getPage(1));
    
    const viewportOriginal = page.getViewport({ scale: DEFAULT_SCALE });
    
    const maxWidth = Math.min(window.innerWidth, IDEAL_MAX_CANVAS_WIDTH);
    const maxHeight = window.innerHeight;
    const scaleWidth = maxWidth / viewportOriginal.width;
    const scaleHeight = maxHeight / viewportOriginal.height;
    
    displayScale = Math.min(scaleWidth, scaleHeight);
    pdfScale = displayScale * window.devicePixelRatio;
    
    pdfScaleFactor = viewportOriginal.width / (viewportOriginal.height * displayScale);
    
    const viewport = page.getViewport({ scale: pdfScale });
    
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

window.toPDFCoords = function(x, y) {
    return {
        x: x / displayScale,
        y: y / displayScale
    };
};

window.toScreenCoords = function(x, y) {
    return {
        x: x * displayScale,
        y: y * displayScale
    };
};


window.addSignatureToPage = function(pageNum, xPercent, yPercent, base64Image) {
    
    signatureLayer.innerHTML = "";
    
    const img = document.createElement("img");
    img.src = base64Image;
    img.classList.add("signature-item");
    img.style.position = "absolute";
    img.style.left = `${100 * xPercent}%`;
    img.style.top = `${100 * yPercent}%`;
    img.style.transform = "translate(-50%, -50%)";
    // img.style.width = "120px";
    img.style.cursor = "move";
    img.style.pointerEvents = "auto";
    // img.style.border = "1px";
    // img.style.borderColor = "black";
    img.setAttribute("draggable", true);
    
    signatureLayer.appendChild(img);
    imgQR = img;
    makeSignatureDraggable(img, signatureLayer);
    updateSignatureData(img);
}

function makeSignatureDraggable(el, onDrop = null) {
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
                    
                    let x = (parseFloat(target.getAttribute('data-x')) || 0) + dx;
                    let y = (parseFloat(target.getAttribute('data-y')) || 0) + dy;
                    
                    
                    
                    target.style.transform = `translate(${x}px, ${y}px)`;
                    target.setAttribute('data-x', x);
                    target.setAttribute('data-y', y);
                },
                async end(event) {
                    updateSignatureData(event.currentTarget);
                    if (typeof ondrop === 'function') {
                        ondrop(resultMove);
                    }
                }
            }
        })
        .resizable({
            edges: { left: true, right: true, bottom: true, top: true },
            modifiers: [
                interact.modifiers.restrictEdges({
                    outer: 'parent'
                }),
                interact.modifiers.restrictSize({
                    min: { width: 50, height: 50 }
                })
            ],
            listeners: {
                move(event) {
                    let { x, y } = event.target.dataset;

                    x = (parseFloat(x) || 0);
                    y = (parseFloat(y) || 0);
                    
                    
                    if (!event.target.dataset.aspectRatio) {
                        const rect = event.target.getBoundingClientRect();
                        event.target.dataset.aspectRatio = rect.width / rect.height;
                    }
                    
                    const aspectRatio = parseFloat(event.target.dataset.aspectRatio);
                    qrScale = aspectRatio;
                    
                    const newWidth = event.rect.width;
                    const newHeight = newWidth / aspectRatio;
                    
                    let scaleW = newWidth / DEFAULT_QR_WIDTH;
                    let scaleH = newHeight / DEFAULT_QR_HEIGHT;
                    
                    Object.assign(event.target.style, {
                        width: `${newWidth}px`,
                        height: `${newHeight}px`,
                        transform: `translate(${x}px, ${y}px)`
                    });
                    
                    Object.assign(event.target.dataset, { x, y });
                },
                async end(event) {
                    updateSignatureData(event.currentTarget);
                }
            }
        });
};

async function updateSignatureData(target) {
    const xOffset = parseFloat(target.getAttribute('data-x')) || 0;
    const yOffset = parseFloat(target.getAttribute('data-y')) || 0;
    
    const wrapper = target.closest('.pdf-page-wrapper');
    const pageNumber = parseInt(wrapper.dataset.pageNumber);
    
    const page = await pdf.getPage(current_page);
    const viewport = page.getViewport({ scale: pdfScale });
    
    const canvasHeight = viewport.height;
    const canvasWidth = viewport.width;
    
    const offsetLeft = target.offsetLeft + xOffset;
    const offsetTop = target.offsetTop + yOffset;
    
    const elWidth = target.offsetWidth;
    const elHeight = target.offsetHeight;
    
    const clientX = offsetLeft;
    // const clientY = (canvasHeight - offsetTop - elHeight);
    const clientY = offsetTop;
    const pdfX = (clientX / pdfScale) * PX_TO_MM * PDFJS_DPI;
    const pdfY = (clientY / pdfScale) * PX_TO_MM * PDFJS_DPI;
    
    const qrSize = {
        width: (elWidth / pdfScale) * PX_TO_MM * PDFJS_DPI,
        height: (elHeight / pdfScale) * PX_TO_MM * PDFJS_DPI
    };
    
    const pdfSize = {
        width: (wrapper.offsetWidth / pdfScale) * PX_TO_MM * PDFJS_DPI,
        height: (wrapper.offsetHeight / pdfScale) * PX_TO_MM * PDFJS_DPI
    }
    
    const result = {
        x: pdfX,
        y: pdfY,
        qrSize,
        pdfSize,
        page: current_page
    };
    
    resultMove = result;
    
    window.dispatchEvent(new CustomEvent('update_pdf_sign_info', {
        detail: result,
        bubbles: true
    }));
}

window.makeSignatureInteractive = function(el, onUpdate = null) {
    makeSignatureDraggable(el, onUpdate);
};
