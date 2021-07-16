<?php include '../inc/head.php'; 
$reportID 	= $_GET['reportID'];
$wbappID 	= $g->Dlookup('wb_roi_ID','wb_roi_reports','wb_roi_report_ID=' . $reportID);
$reportname	= $g->Dlookup('roiReportName','wb_roi_reports','wb_roi_report_ID=' . $reportID);
$filename	= '../assets/customwb/' . $wbappID . '/pdf/preview-' . $reportID . '.pdf';
$link		= '//www.theroishop.com/webapps/assets/customwb/' . $wbappID . '/pdf/preview-' . $reportID . '.pdf';
?>


    <div id="wrapper">

        

        <div class="wrapper wrapper-content animated fadeIn">
            <div class="row">
                <div class="col-sm-12">
                    <div class="ibox">
                        <div class="ibox-content border-sbottom">
                            <h4>Preview of <strong><?php echo $reportname; ?></strong></h4>
                            <ul>
                            <li>Full Path: <a href="<?php echo $link; ?>"><?php echo $link; ?></a></li>
                            <li>Date Created: <?php echo date("F d Y H:i:s.", filectime($filename)); ?></li>
                            <li>File Size: <?php echo filesize($filename)/1000;?> KB</li>
                            
                           
                            
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
                    <div class="text-center pdf-toolbar">

                            <div class="btn-group">
                                <button id="prev" class="btn btn-white"><i class="fa fa-long-arrow-left"></i> <span class="hidden-xs">Previous</span></button>
                                <button id="next" class="btn btn-white"><i class="fa fa-long-arrow-right"></i> <span class="hidden-xs">Next</span></button>
                                <button id="zoomin" class="btn btn-white"><i class="fa fa-search-minus"></i> <span class="hidden-xs">Zoom In</span></button>
                                <button id="zoomout" class="btn btn-white"><i class="fa fa-search-plus"></i> <span class="hidden-xs">Zoom Out</span> </button>
                                <button id="zoomfit" class="btn btn-white"> 100%</button>
                                <span class="btn btn-white hidden-xs">Page: </span>

                            <div class="input-group">
                                <input type="text" class="form-control" id="page_num">

                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-white" id="page_count">/ 22</button>
                                </div>
                            </div>

                                </div>
                        </div>







            <div class="text-center m-t-md">
                <canvas id="the-canvas" class="pdfcanvas border-left-right border-top-bottom b-r-md"></canvas>
            </div>

            <div class="footer">
                <div class="pull-right">
                    10GB of <strong>250GB</strong> Free.
                </div>
                <div>
                    <strong>Copyright</strong> Example Company &copy; 2014-2017
                </div>
            </div>

        </div>

        </div>


		<?php include '../inc/footer.php';?>

        <script src="../assets/js/plugins/pdfjs/pdf.js"></script>


    <script id="script">
        //
        // If absolute URL from the remote server is provided, configure the CORS
        // header on that server.
        //
        
		var url = '../assets/customwb/<?php echo $wbappID; ?>/pdf/preview-<?php echo $reportID; ?>.pdf';

        var pdfDoc = null,
                pageNum = 1,
                pageRendering = false,
                pageNumPending = null,
                scale = 2,
                zoomRange = 0.25,
                canvas = document.getElementById('the-canvas'),
                ctx = canvas.getContext('2d');

        /**
         * Get page info from document, resize canvas accordingly, and render page.
         * @param num Page number.
         */
        function renderPage(num, scale) {
            pageRendering = true;
            // Using promise to fetch the page
            pdfDoc.getPage(num).then(function(page) {
                var viewport = page.getViewport(scale);
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                // Render PDF page into canvas context
                var renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                var renderTask = page.render(renderContext);

                // Wait for rendering to finish
                renderTask.promise.then(function () {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        // New page rendering is pending
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });

            // Update page counters
            document.getElementById('page_num').value = num;
        }

        /**
         * If another page rendering in progress, waits until the rendering is
         * finised. Otherwise, executes rendering immediately.
         */
        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num,scale);
            }
        }

        /**
         * Displays previous page.
         */
        function onPrevPage() {
            if (pageNum <= 1) {
                return;
            }
            pageNum--;
            var scale = pdfDoc.scale;
            queueRenderPage(pageNum, scale);
        }
        document.getElementById('prev').addEventListener('click', onPrevPage);

        /**
         * Displays next page.
         */
        function onNextPage() {
            if (pageNum >= pdfDoc.numPages) {
                return;
            }
            pageNum++;
            var scale = pdfDoc.scale;
            queueRenderPage(pageNum, scale);
        }
        document.getElementById('next').addEventListener('click', onNextPage);

        /**
         * Zoom in page.
         */
        function onZoomIn() {
            if (scale >= pdfDoc.scale) {
                return;
            }
            scale += zoomRange;
            var num = pageNum;
            renderPage(num, scale)
        }
        document.getElementById('zoomin').addEventListener('click', onZoomIn);

        /**
         * Zoom out page.
         */
        function onZoomOut() {
            if (scale >= pdfDoc.scale) {
                return;
            }
            scale -= zoomRange;
            var num = pageNum;
            queueRenderPage(num, scale);
        }
        document.getElementById('zoomout').addEventListener('click', onZoomOut);

        /**
         * Zoom fit page.
         */
        function onZoomFit() {
            if (scale >= pdfDoc.scale) {
                return;
            }
            scale = 1;
            var num = pageNum;
            queueRenderPage(num, scale);
        }
        document.getElementById('zoomfit').addEventListener('click', onZoomFit);


        /**
         * Asynchronously downloads PDF.
         */
        PDFJS.getDocument(url).then(function (pdfDoc_) {
            pdfDoc = pdfDoc_;
            var documentPagesNumber = pdfDoc.numPages;
            document.getElementById('page_count').textContent = '/ ' + documentPagesNumber;

            $('#page_num').on('change', function() {
                var pageNumber = Number($(this).val());

                if(pageNumber > 0 && pageNumber <= documentPagesNumber) {
                    queueRenderPage(pageNumber, scale);
                }

            });

            // Initial/first page rendering
            renderPage(pageNum, scale);
        });
    </script>


</body>

</html>
