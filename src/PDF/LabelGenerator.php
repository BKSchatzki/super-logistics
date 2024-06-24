<?php

namespace SL\PDF;
use SL\PDF\SLPDF;

class LabelGenerator
{
    public function generate($transaction): string
    {
        // Create a new TCPDF object
        $pdf = new SLPDF();
        $this->setMetaData($pdf);
        $this->configPDF($pdf);

        $totalItems = 0;
        foreach ($transaction->items as $item) {
            $totalItems += (int)($item->pcs) || (int)($item->pcs) == 0 ? (int)($item->pcs) : 1;
        }

        for ($i = 0; $i < $totalItems; $i++) {
            $pdf->AddPage();
            $this->setStaticContent($pdf, $transaction);
            $pdf->SetXY(140, 115);
            $pdf->Cell(30, 10, 'PIECE #', 0, 0, 'C');
            $pdf->SetXY(115, 120);
            $pdf->SetFont('helvetica', 'B', 120);
            $pdf->Cell(30, 10, ($i + 1), 0, 0, 'C');
            $pdf->SetXY(142, 153);
            $pdf->SetFont('helvetica', '', 80);
            $pdf->Cell(30, 10, '/', 0, 0, 'C');
            $pdf->SetXY(160, 170);
            $pdf->SetFont('helvetica', 'B', 60);
            $pdf->Cell(30, 10, $totalItems, 0, 0, 'C');
            $pdf->SetFont('helvetica', '', 15);
        }
        $pdf->AddPage();

        return $pdf->Output('sample.pdf', 'S');
    }

    private function setMetaData($pdf): void
    {
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('ThinkSTG');
        $pdf->SetTitle('Shipping Label');
        $pdf->SetSubject('TCPDF Tutorial');
    }

    private function configPDF($pdf):void {
        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 15);
    }

    private function setStaticContent($pdf, $transaction):void {
        // Show Logo
        $this->placeImageOrBust($pdf, $transaction->show->entity, 'Show:', 25, 20);
        // Client Logo
        $this->placeImageOrBust($pdf, $transaction->client, 'Client:', 125, 20);
        // Shipper Name and Address
        $pdf->SetXY(40, 50);
        $pdf->Cell(30, 10, 'SHIPPER', 0, 0, 'C');
        $this->makeAddressTable($pdf, $transaction->shipper, 15, 70);
        // Show Information
        $pdf->SetXY(40, 160);
        $pdf->Cell(30, 10, 'DELIVER TO', 0, 0, 'C');
        $this->makeAddressTable($pdf, $transaction->show->entity, 15, 180);
        // QR Code
        $qrCodeData = [
            'trans_id' => $transaction->id,
            'show_id' => $transaction->show_id,
            'client_id' => $transaction->client_id,
            'show_place_id' => $transaction->show_place_id,
            'shipper_id' => $transaction->shipper_id,
        ];
        $qrCodeData = json_encode($qrCodeData);
        $pdf->write2DBarcode($qrCodeData, 'QRCODE,H', 130, 55, 50, 50, null, 'N');
        // Zone
        $pdf->SetXY(140, 205);
        $pdf->Cell(30, 10, 'ZONE', 0, 0, 'C');
        $pdf->SetXY(110, 220);
        $pdf->SetFont('helvetica', 'B', 50);
        $pdf->MultiCell(80, 50, $transaction->showPlace->name, 0, 'C', 0, 0);
        $pdf->SetFont('helvetica', '', 15);
    }

    private function placeImageOrBust($pdf, $entity, $fallbackLabel, $x = 10, $y = 10): void {

        if ($entity === null) {
            $pdf->Write(0, 'No entity provided');
            return;
        }

        if ($entity->logo_path) {
            $pdf->Image($entity->logo_path, $x, $y, 60, 20, '', '', '', false, 300, '', false, false, 0, '', false, false);
            $pdf->Rect($x, $y, 60, 20, 'D'); // Draw a rectangle around the image
        } else {
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect($x, $y, 60, 20, 'D');
            $pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
            $pdf->SetXY($x+3, $y+3);
            $pdf->MultiCell(56, 16, $fallbackLabel . "\n" . $entity->name, 0, 'C', false, 0);
        }
    }

    private function makeAddressTable($pdf, $entity, $x, $y):void {

        $labelWidth = 30;
        $cellWidth = 55;
        $cellHeight = 15;
        $addressHeight = 30;

        $address = $entity->address . "\n" . $entity->city . ', ' . $entity->state . "\n" . $entity->zip;

        $pdf->SetXY($x, $y);
        $pdf->Cell($labelWidth, $cellHeight, 'Name', 1, 0, 'C');
        $pdf->Cell($cellWidth, $cellHeight, $entity->name, 1, 0, 'C');

        $y += $cellHeight;
        $pdf->SetXY($x, $y);
        $pdf->Cell($labelWidth, $addressHeight, 'Address', 1, 0, 'C');
        $pdf->SetFillColor(255, 255, 255);
        $pdf->MultiCell($cellWidth, $addressHeight, $address, 1, false, 'C');

        $y += $addressHeight;
        $pdf->SetXY($x, $y);
        $pdf->Cell($labelWidth, $cellHeight, 'Phone', 1, 0, 'C');
        $pdf->Cell($cellWidth, $cellHeight, $entity->phone, 1, 0, 'C');

        $y += $cellHeight;
        $pdf->SetXY($x, $y);
        $pdf->Cell($labelWidth, $cellHeight, 'Email', 1, 0, 'C');
        $pdf->Cell($cellWidth, $cellHeight, $entity->email, 1, 0, 'C');
    }
}

