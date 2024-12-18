<?php

namespace BigTB\SL\API\PDF;

class ExternalLabelGenerator
{
    public function generate($transaction): string
    {
        // Create a new TCPDF object
        $pdf = new SLPDF();
        $this->setMetaData($pdf);
        $this->configPDF($pdf);
        $pdf->AddPage();
        $this->setStaticContent($pdf, $transaction);

        return $pdf->Output('sample.pdf', 'S');
    }

    private function setMetaData($pdf): void
    {
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Client');
        $pdf->SetTitle('Label for Logistics Partner');
        $pdf->SetSubject('Transaction Information');
    }

    private function configPDF($pdf):void {
        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(0);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(true, 0);

        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 15);
    }

    private function setStaticContent($pdf, $transaction):void
    {

        $qrCodeData = json_encode($transaction);
        $pdf->write2DBarcode($qrCodeData, 'QRCODE,M', 38, 12, 140, 140, null, 'N');
    }
}

