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
            $pdf->SetXY(30, 85);
            $pdf->SetFont('helvetica', 'B', 22);
            $pdf->Cell(30, 10, ($i + 1), 0, 0, 'C');
            $pdf->SetXY(56, 85);
            $pdf->SetFont('helvetica', '', 22);
            $pdf->Cell(10, 10, '/', 0, 0, 'C');
            $pdf->SetXY(60, 85);
            $pdf->SetFont('helvetica', 'B', 22);
            $pdf->Cell(30, 10, $totalItems, 0, 0, 'C');
            $pdf->SetFont('helvetica', '', 15);
        }
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

        $transaction = json_decode($transaction);
        $qrCodeData = [
            'trans_id' => $transaction->id,
            'show_id' => $transaction->show_id,
            'client_id' => $transaction->client_id,
            'show_place_id' => $transaction->show_place->id,
            'shipper_id' => $transaction->shipper_id,
        ];
        $qrCodeData = json_encode($qrCodeData);
        $pdf->write2DBarcode($qrCodeData, 'QRCODE,H', 37, 10, 30, 30, null, 'N');
        // Exhibitor
        $this->placeLabel($pdf, 12, 45, 'Exhibitor: ');
        $this->placeContent($pdf, 32, 45, $transaction->exhibitor->name);
        // Client
        $this->placeLabel($pdf, 12, 55, 'Client: ');
        $this->placeContent($pdf, 32, 55, $transaction->client->name);
        // Shipment
        $this->placeLabel($pdf, 12, 65, 'Shipment: ');
        $this->placeContent($pdf, 32, 65, $transaction->shipment);
        // Zone
        $this->placeLabel($pdf, 12, 75, 'Zone: ');
        $this->placeContent($pdf, 32, 75, $transaction->show_place->name, 0, 0, 'C');
        // Pieces (leave a space here)
        $this->placeLabel($pdf, 12, 85, 'Piece #: ');
        // Pallet + Weight
        $this->placeLabel($pdf, 12, 95, 'Pallet: ');
        $this->placeContent($pdf, 32, 95, $transaction->pallet_no, 0, 0, 'C');
        // Receiver
        $this->placeLabel($pdf, 12, 105, 'Receiver: ');
        $this->placeContent($pdf, 32, 105, $transaction->receiver, 0, 0, 'C');
        // Carrier
        $this->placeLabel($pdf, 12, 115, 'Carrier: ');
        $this->placeContent($pdf, 32, 115, $transaction->carrier->name, 0, 0, 'C');
    }

    private function placeLabel($pdf, $x, $y, $label) {
        $pdf->SetXY($x, $y);
        $pdf->SetFont('helvetica', '', 15);
        $pdf->Cell(20, 10, $label, 0, 0, 'R');
    }

    private function placeContent($pdf, $x, $y, $content) {
        $pdf->SetXY($x, $y);
        $pdf->SetFont('helvetica', 'B', 22);
        $pdf->MultiCell(150, 50, $content, 0, 'L', 0, 0);
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

