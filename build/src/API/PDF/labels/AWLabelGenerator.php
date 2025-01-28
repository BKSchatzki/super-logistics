<?php

namespace BigTB\SL\API\PDF;
use BigTB\SL\API\PDF\SLPDF;

class AWLabelGenerator
{
    public static function generate($transaction): string
    {
        // Create a new TCPDF object
        $pdf = new SLPDF();
        self::setMetaData($pdf);
        self::configPDF($pdf);

        $totalItems = 0;
        foreach ($transaction->items as $item) {
            $totalItems += (int)($item->pcs) || (int)($item->pcs) == 0 ? (int)($item->pcs) : 1;
        }

        for ($i = 0; $i < $totalItems; $i++) {
            $pdf->AddPage();
            self::setStaticContent($pdf, $transaction);
            $pdf->SetXY(65, 61);
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->Cell(30, 10, ($i + 1), 0, 0, 'R');
            $pdf->SetXY(76, 61);
            $pdf->SetFont('helvetica', '', 14);
            $pdf->Cell(10, 10, '/', 0, 0, 'C');
            $pdf->SetXY(70, 61);
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->Cell(30, 10, $totalItems, 0, 0, 'L');
            $pdf->SetFont('helvetica', '', 13);
        }

        $pdf->deletePage($pdf->getNumPages());
        return $pdf->Output('sample.pdf', 'S');
    }

    private static function setMetaData($pdf): void
    {
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('ThinkSTG');
        $pdf->SetTitle('Shipping Label');
        $pdf->SetSubject('TCPDF Tutorial');
    }

    private static function configPDF($pdf):void {
        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(true, 0);

        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 15);
    }

    private static function setStaticContent($pdf, $transaction):void
    {

        $transaction = json_decode($transaction);
        $qrCodeData = [
            'trans_id' => $transaction->id,
            'show_id' => $transaction->show_id,
            'client_id' => $transaction->client_id,
            'show_place_id' => $transaction->show_place->id,
            'shipper_id' => $transaction->shipper_id,
        ];
        $qrCodeData = json_encode($qrCodeData);
        $pdf->write2DBarcode($qrCodeData, 'QRCODE,H', 10, 37, 30, 30, null, 'N');
        // Exhibitor
        self::placeLabel($pdf, 50, 5, 'Exhibitor: ');
        self::placeContent($pdf, 70, 7, $transaction->exhibitor->name);
        // Client
        self::placeLabel($pdf, 50, 21, 'Client: ');
        self::placeContent($pdf, 70, 23, $transaction->client->name);
        // Shipment
        self::placeLabel($pdf, 50, 37, 'Shipment: ');
        self::placeContent($pdf, 70, 39, $transaction->shipment);
        // Zone
        self::placeLabel($pdf, 50, 45, 'Zone: ');
        self::placeContent($pdf, 70, 47, $transaction->show_place->name, 0, 0, 'C');
        // Booth
        self::placeLabel($pdf, 50, 53, 'Booth: ');
        self::placeContent($pdf, 70, 55, $transaction->booth, 0, 0, 'C');
        // Pieces (leave a space here)
        self::placeLabel($pdf, 50, 61, 'Piece #: ');
        // Pallet + Weight
        self::placeLabel($pdf, 50, 69, 'Pallet: ');
        self::placeContent($pdf, 70, 71, $transaction->pallet_no, 0, 0, 'C');
        // Receiver
        self::placeLabel($pdf, 50, 77, 'Receiver: ');
        self::placeContent($pdf, 70, 79, $transaction->receiver, 0, 0, 'C');
        // Carrier
        self::placeLabel($pdf, 50, 85, 'Carrier: ');
        self::placeContent($pdf, 70, 87, $transaction->carrier->name, 0, 0, 'C');
    }

    private static function placeLabel($pdf, $x, $y, $label) {
        $pdf->SetXY($x, $y);
        $pdf->SetFont('helvetica', '', 13);
        $pdf->Cell(20, 10, $label, 0, 0, 'R');
    }

    private static function placeContent($pdf, $x, $y, $content) {
        $pdf->SetXY($x, $y);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->MultiCell(80, 50, $content, 0, 'L', 0, 0);
    }

    private static function placeImageOrBust($pdf, $entity, $fallbackLabel, $x = 10, $y = 10): void {

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

    private static function makeAddressTable($pdf, $entity, $x, $y):void {

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

