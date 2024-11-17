<?php 
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->setChroot(__DIR__);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->addInfo("Title", "Faculty Ranking");
$dompdf->render();
$dompdf->stream('Faculty Ranking.pdf', ["Attachment" => 0]);
?>