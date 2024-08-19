<?php

namespace SL\PDF;

use SL\PDF\SLPDF;

class ShowReportGenerator
{
    public function generate($transactions, $start_date, $end_date): string
    {
        // Create a new TCPDF object
        $transactionsArray = $transactions->toArray();
        error_log("First transaction: " . print_r($transactionsArray, true));
        $show_name = isset($transactionsArray[0]) ? $transactionsArray[0]['show']['entity']['name'] : null;
        $details = [
            'show_name' => $show_name,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];
        $pdf = new SLPDF( 'L', 'mm', 'LETTER', 'show_report', $details);
        $this->setMetaData($pdf);
        $this->configPDF($pdf);

        $pdf->AddPage();
        $this->placeFixedContent($pdf, $show_name);
        $pdf->SetXY(10, 86);

        foreach ($transactions as $t) {

            $items = $this->sortItemsInfo($t->items);
            $total_pcs = $this->getTotalItems($t);
            $total_weight = $this->getTotalWeight($t);
            $billable_weight = $this->getBillableWeight($t, $total_weight);
            $notes = $this->getNotes($t);

            $pdf->SetFont('helvetica', '', 6);
            $pdf->Cell(10, 6, $t->id, 1, 0, 'C');
            $pdf->Cell(15, 6, substr($t->shipper->name, 0, 12), 1, 0, 'C');
            $pdf->Cell(15, 6, substr($t->exhibitor->name, 0, 12), 1, 0, 'C');
            $pdf->Cell(10, 6, substr($t->carrier->name, 0, 8), 1, 0, 'C');
            $pdf->Cell(15, 6, substr($t->tracking, 0, 12), 1, 0, 'C');
            $pdf->Cell(15, 6, substr($t->client->name, 0, 12), 1, 0, 'C');
            $pdf->Cell(15, 6, substr($t->shipment, 0, 12),1, 0, 'C');
            $pdf->Cell(15, 6, substr($t->showPlace->name, 0, 12), 1, 0, 'C');
            $pdf->Cell(8, 6, $items['crate']->pcs ?? 0, 1, 0, 'C');
            $pdf->Cell(8, 6, $items['carton']->pcs ?? 0, 1, 0, 'C');
            $pdf->Cell(8, 6, $items['skid']->pcs ?? 0, 1, 0, 'C');
            $pdf->Cell(8, 6, $items['fiber_case']->pcs ?? 0, 1, 0, 'C');
            $pdf->Cell(8, 6, $items['carpet']->pcs ?? 0, 1, 0, 'C');
            $pdf->Cell(8, 6, $items['misc']->pcs ?? 0, 1, 0, 'C');
            $pdf->Cell(10, 6, $total_pcs, 1, 0, 'C');
            $pdf->Cell(10, 6, $total_weight, 1, 0, 'C');
            $pdf->Cell(10, 6, $billable_weight, 1, 0, 'C');
            $pdf->Cell(30, 6, $notes, 1, 0, 'C');
            $pdf->Cell(10, 6, substr($t->shipment, 0, 12), 1, 0, 'C');
            $pdf->Cell(10, 6, substr($t->receiver, 0, 12), 1, 0, 'C');
            $pdf->Cell(10, 6, substr($t->trailer, 0, 12), 1, 0, 'C');
            $pdf->Cell(10, 6, $t->created_at, 1, 0, 'C');
            $pdf->Ln();
        }
        return $pdf->Output('sample.pdf', 'S');
    }

    private function sortItemsInfo($items): array
    {
        $sortedItems = [];
        $itemsKey = [
            2 => 'crate',
            3 => 'carton',
            4 => 'skid',
            5 => 'fiber_case',
            6 => 'carpet',
            7 => 'misc'
        ];
        foreach ($items as $item) {
            $sortedItems[$itemsKey[$item->type]] = $item;
        }
        return $sortedItems;
    }

    private function getTotalItems($transaction): int {
        $totalPcs = 0;
        foreach ($transaction->items as $item) {
            $totalPcs += $item->pcs;
        }
        return $totalPcs;
    }

    private function getTotalWeight($transaction): int {
        $totalWeight = 0;
        foreach ($transaction->items as $item) {
            $totalWeight += ($item->weight * $item->pcs);
        }
        return $totalWeight;
    }

    private function getBillableWeight($transaction, $total_weight): int {
        $billable_weight = $transaction->show->min_carat_weight;
        $carat_increment = $transaction->show->carat_weight_inc;
        while ($billable_weight < $total_weight) {
            $billable_weight += $carat_increment;
        }
        return $billable_weight;
    }

    private function placeFixedContent($pdf, $transactionsArray): void
    {
        $pdf->SetXY( 10, 25);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->MultiCell(25, 10, 'Piece Summary Totals', 1, 'C', false, 1, '', '', true);
        $pdf->SetXY( 35, 25);
        $pdf->Cell(25, 10, 'Total Pcs', 1, 0, 'C');
        $pdf->Cell(25, 10, 'Weight', 1, 0, 'C');
        $pdf->Cell(25, 10, 'Billable Weight', 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(25, 6, 'Crate', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Total Pcs', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Weight', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Billable Weight', 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(25, 6, 'Carton', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Total Pcs', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Weight', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Billable Weight', 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(25, 6, 'Skid', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Total Pcs', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Weight', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Billable Weight', 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(25, 6, 'Fiber Case', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Total Pcs', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Weight', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Billable Weight', 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(25, 6, 'Carpet', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Total Pcs', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Weight', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Billable Weight', 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(25, 6, 'Misc', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Total Pcs', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Weight', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Billable Weight', 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(25, 6, 'Totals', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Total Pcs', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Weight', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Billable Weight', 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetXY(10, 80);
        $pdf->SetFont('helvetica', 'B', 6);
        $pdf->Cell(10, 6, 'Rec ID', 1, 0, 'C');
        $pdf->Cell(15, 6, 'Shipper', 1, 0, 'C');
        $pdf->Cell(15, 6, 'Exhibitor', 1, 0, 'C');
        $pdf->Cell(10, 6, 'Carrier', 1, 0, 'C');
        $pdf->Cell(15, 6, 'Tracking', 1, 0, 'C');
        $pdf->Cell(15, 6, 'Client', 1, 0, 'C');
        $pdf->Cell(15, 6, 'Shipment', 1, 0, 'C');
        $pdf->Cell(15, 6, 'Zone', 1, 0, 'C');
        $pdf->Cell(8, 6, 'Crate', 1, 0, 'C');
        $pdf->Cell(8, 6, 'Carton', 1, 0, 'C');
        $pdf->Cell(8, 6, 'Skid', 1, 0, 'C');
        $pdf->Cell(8, 6, 'Fbr Cse', 1, 0, 'C');
        $pdf->Cell(8, 6, 'Carpet', 1, 0, 'C');
        $pdf->Cell(8, 6, 'Misc', 1, 0, 'C');
        $pdf->Cell(10, 6, 'Total Pcs', 1, 0, 'C');
        $pdf->Cell(10, 6, 'Weight', 1, 0, 'C');
        $pdf->Cell(10, 6, 'Billable', 1, 0, 'C');
        $pdf->Cell(30, 6, 'Remarks / Notes ???', 1, 0, 'C');
        $pdf->Cell(10, 6, 'Shipment', 1, 0, 'C');
        $pdf->Cell(10, 6, 'Receiver', 1, 0, 'C');
        $pdf->Cell(10, 6, 'Trailer', 1, 0, 'C');
        $pdf->Cell(10, 6, 'Date', 1, 0, 'C');
    }

    private function setMetaData($pdf): void
    {
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('ThinkSTG');
        $pdf->SetTitle('Show Report');
    }

    private function configPDF($pdf): void
    {

        // Set margins
        $pdf->SetMargins(10, 27, PDF_MARGIN_RIGHT);
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

