<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//UserSpice Required
require_once '../../../../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}

$IGYear = date("Y")+1286;
$IGDate = date("d-M");

require("pdflib.php");

function certificate_print_text($pdf, $x, $y, $align, $font='freeserif', $style, $size = 10, $text, $width = 0) {
    $pdf->setFont($font, $style, $size);
    $pdf->SetXY($x, $y);
    $pdf->writeHTMLCell($width, 0, '', '', $text, 0, 0, 0, true, $align);
}

$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator("The Hull Seals");
$pdf->SetTitle("Certificate of Completion: The Hull Seals");
$pdf->SetProtection(array('modify'));
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(false, 0);
$pdf->AddPage();

    $x = 10;
    $y = 40;

    $sigx = 24;
    $sigy = 50;
    $sig = realpath("signature.gif");

    $sigxuk = 24;
    $sigyuk = 50;
    $siguk = realpath("uk.gif");

    $custx = 30;
    $custy = 230;

    $wmarkh = 120;
    $wmarkw = 140;
    $wmark = realpath("./watermark.jpg");

    $brdrx = 0;
    $brdry = 0;
    $brdrw = 210;
    $brdrh = 297;
    $codey = 250;


$fontsans = 'helvetica';
$fontserif = 'times';

// border
$pdf->SetLineStyle(array('width' => 1.5, 'color' => array(0,0,0)));
$pdf->Rect(10, 10, 277, 190);
// create middle line border
$pdf->SetLineStyle(array('width' => 0.2, 'color' => array(64,64,64)));
$pdf->Rect(13, 13, 271, 184);
// create inner line border
$pdf->SetLineStyle(array('width' => 1.0, 'color' => array(128,128,128)));
$pdf->Rect(16, 16, 265, 178);


// Set alpha to semi-transparency
if (file_exists($wmark)) {
    $pdf->SetAlpha(0.2);
    $pdf->Image($wmark, '',20,211,150,'','','C','','','C');
}

$pdf->SetAlpha(1);
if (file_exists($sig)) {
  $pdf->Image($sig, 35,140,'','');
}
if (file_exists($siguk)) {
  $pdf->Image($siguk, 175,140,'','');
}

// Add text
$pdf->SetTextColor(0, 0, 120);
certificate_print_text($pdf, $x, $y, 'C', $fontsans, '', 30, "Certificate of Completion");
$pdf->SetTextColor(0, 0, 0);
certificate_print_text($pdf, $x, $y + 20, 'C', $fontserif, '', 20, "This is to certify that");
certificate_print_text($pdf, $x, $y + 36, 'C', $fontsans, '', 30, echousername($user->data()->id));
certificate_print_text($pdf, $x, $y + 55, 'C', $fontsans, '', 20, "has successfully completed");
certificate_print_text($pdf, $x, $y + 72, 'C', $fontsans, '', 20, "Hull Seals Basic Training");
certificate_print_text($pdf, $x, $y + 85, 'C', $fontsans, '', 10, "on");
certificate_print_text($pdf, $x, $y + 92, 'C', $fontsans, '', 18, $IGDate . "-" . $IGYear);
certificate_print_text($pdf, $x+25, $y + 120, '', $fontsans, '', 14, "________________________");
certificate_print_text($pdf, $x+28, $y + 128, '', $fontsans, '', 14, "Halpy, Mascot of the Seals");
certificate_print_text($pdf, $x+190, $y + 120, '', $fontsans, '', 14, "________________________");
certificate_print_text($pdf, $x+198, $y + 128, '', $fontsans, '', 14, "Unknown, Head Trainer");
header ("Content-Type: application/pdf");
echo $pdf->Output('', 'S');