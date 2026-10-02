jQuery(document).ready(function($) {
    
    var canvas = document.getElementById('vault-pdf-canvas');
    if(!canvas) return;

    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
    } else {
        console.error("PDF.js engine missing.");
        return;
    }

    var url = canvas.getAttribute('data-src');
    var pdfDoc = null, pageNum = 1, scale = 1.0;
    var ctx = canvas.getContext('2d');

    pdfjsLib.getDocument(url).promise.then(function(pdfDoc_){
        pdfDoc = pdfDoc_;
        $('#page-count').text(pdfDoc.numPages);
        $('#vpe-loading-msg').fadeOut(300);
        renderPage(pageNum);
    }).catch(function(error) {
        console.error("Payload streaming failure: ", error);
        $('#vpe-loading-msg p').text('Failed to load document.');
    });

    function renderPage(num) {
        pdfDoc.getPage(num).then(function(page) {
            var viewport = page.getViewport({ scale: scale });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            var renderContext = { canvasContext: ctx, viewport: viewport };

            page.render(renderContext).promise.then(function() {
                $('#page-num').text(num);
                $('#zoom-percent').text(Math.round(scale * 100) + '%');
            });
        });
    }

    $('#prev-page').on('click', function() { if (pageNum <= 1) return; pageNum--; renderPage(pageNum); });
    $('#next-page').on('click', function() { if (pageNum >= pdfDoc.numPages) return; pageNum++; renderPage(pageNum); });
    $('#zoom-in').on('click', function() { if (scale >= 3.0) return; scale += 0.25; renderPage(pageNum); });
    $('#zoom-out').on('click', function() { if (scale <= 0.5) return; scale -= 0.25; renderPage(pageNum); });

    $('#fullscreen-btn').on('click', function() {
        var wrapper = document.querySelector('.vault-pdf-wrapper');
        if (!document.fullscreenElement) {
            if (wrapper.requestFullscreen) wrapper.requestFullscreen();
            else if (wrapper.webkitRequestFullscreen) wrapper.webkitRequestFullscreen();
        } else {
            if (document.exitFullscreen) document.exitFullscreen();
        }
    });

    $(window).on('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && (e.keyCode === 83 || e.keyCode === 80)) e.preventDefault();
    });
});