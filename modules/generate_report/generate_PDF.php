<?php 
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->setChroot(__DIR__);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->addInfo("Title", "Summary Report");
$dompdf->render();
$dompdf->stream('Summary Report.pdf', ["Attachment" => 0]);
?>
