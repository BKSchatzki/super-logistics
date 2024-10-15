<?php

namespace SL\PDF;

use SL\PDF\SLPDF;

class PalletManifestGenerator
{
    public function generate($transactions): string
    {
        // Create a new TCPDF object
        $transactionsArray = $transactions->toArray();
        $pallet_num = isset($transactionsArray[0]) ? $transactionsArray[0]['pallet_no'] : null;
        $pdf = new SLPDF($orientation = 'L', 'mm', 'LETTER', 'pallet_manifest', $pallet_num);
        $this->setMetaData($pdf);
        $this->configPDF($pdf);

        $pdf->AddPage();
        $pdf->SetXY(10, 40);

        foreach ($transactions as $t) {
            $pdf->SetFont('helvetica', '', 8);
            $pdf->Cell(15, 6, $t->id, 1);
            $pdf->Cell(25, 6, substr($t->exhibitor->name, 0, 15), 1);
            $pdf->Cell(15, 6, substr($t->carrier->name, 0, 15), 1);
            $pdf->Cell(30, 6, substr($t->tracking, 0, 15), 1);
            $pdf->Cell(25, 6, substr($t->showPlace->name, 0, 15), 1);
            $pdf->Cell(15, 6, substr($t->booth, 0, 15), 1);
            $pdf->Cell(15, 6, self::countItems($t), 1);
            $pdf->Cell(45, 6, substr(self::getNotes($t), 0, 30), 1);
            $pdf->Cell(25, 6, substr($t->shipment, 0, 15), 1);
            $pdf->Cell(15, 6, substr($t->pallet_no, 0, 15), 1);
            $pdf->Cell(15, 6, substr($t->trailer, 0, 15), 1);
            $pdf->Cell(25, 6, \Carbon\Carbon::parse($t->created_at)->format('m/d/y'), 1);
            $pdf->Ln();
        }
        return $pdf->Output('sample.pdf', 'S');
    }

    private function setMetaData($pdf): void
    {
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('ThinkSTG');
        $pdf->SetTitle('Pallet Manifest');
    }

    private function configPDF($pdf): void
    {

        // Set margins
        $pdf->SetMargins(10, 40, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 15);
    }

    private function countItems($transaction): int
    {
        $totalPcs = 0;
        foreach ($transaction->items as $item) {
            $totalPcs += $item->pcs;
        }
        return $totalPcs;
    }

    private function getNotes($transaction): string
    {
        $notes = [];
        foreach ($transaction->updates as $update) {
            $notes[] = $update->note;
        }
        return implode("\n", $notes);
    }
}

