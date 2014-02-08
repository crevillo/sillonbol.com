<?php

use Disqus\Export\Processor as ExportProcessor,
    Disqus\Export\Exporter\SbComments as SbCommentsExporter,
    Disqus\Export\Formatter\DisqusWXR as DisqusFormatter;

$processor = new ExportProcessor(
    new SbCommentsExporter(),
    new DisqusFormatter()
);
$processor->export();
echo $processor->render();
?>
