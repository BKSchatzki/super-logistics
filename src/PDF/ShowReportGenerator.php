<?php

namespace SL\PDF;

use SL\PDF\SLPDF;

class ShowReportGenerator
{
    public $itemsKey = [
        2 => 'carton',
        3 => 'crate',
        4 => 'skid',
        5 => 'fiber_case',
        6 => 'carpet',
        7 => 'misc'
    ];

    private $shippingKey = [
        1 => 'LTL',
        2 => 'FTL',
        3 => 'Small Pack'
    ];

    public function generate($transactions, $start_date, $end_date): string
    {
        // Create a new TCPDF object
        $transactionsArray = $transactions->toArray();
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
        $this->placeFixedContent($pdf, $transactionsArray);
        $pdf->SetXY(10, 86);

        foreach ($transactions as $t) {

            $items = $this->sortItemsInfo($t->items);
            $total_pcs = $this->getTotalTxnItems($t);
            $total_weight = $this->getTotalTxnWeight($t);
            $billable_weight = $this->getBillableWeight($t, $total_weight);
            $notes = $this->getNotes($t);

            $pdf->SetFont('helvetica', '', 6);
            $pdf->Cell(4, 6, $t->id, 1, 0, 'C');
            $pdf->Cell(15, 6, substr($t->shipper->name, 0, 12), 1, 0, 'C');
            $pdf->Cell(15, 6, substr($t->exhibitor->name, 0, 12), 1, 0, 'C');
            $pdf->Cell(10, 6, substr($t->carrier->name, 0, 8), 1, 0, 'C');
            $pdf->Cell(15, 6, substr($t->tracking, 0, 12), 1, 0, 'C');
            $pdf->Cell(15, 6, substr($t->client->name, 0, 12), 1, 0, 'C');
            $pdf->Cell(15, 6, substr($t->shipment, 0, 12),1, 0, 'C');
            $pdf->Cell(15, 6, substr($t->showPlace->name, 0, 12), 1, 0, 'C');
            $pdf->Cell(10, 6, substr($t->booth, 0, 12), 1, 0, 'C');
            $pdf->Cell(8, 6, $items['crate']->pcs ?? 0, 1, 0, 'C');
            $pdf->Cell(8, 6, $items['carton']->pcs ?? 0, 1, 0, 'C');
            $pdf->Cell(8, 6, $items['skid']->pcs ?? 0, 1, 0, 'C');
            $pdf->Cell(8, 6, $items['fiber_case']->pcs ?? 0, 1, 0, 'C');
            $pdf->Cell(8, 6, $items['carpet']->pcs ?? 0, 1, 0, 'C');
            $pdf->Cell(8, 6, $items['misc']->pcs ?? 0, 1, 0, 'C');
            $pdf->Cell(10, 6, $total_pcs, 1, 0, 'C');
            $pdf->Cell(10, 6, $total_weight, 1, 0, 'C');
            $pdf->Cell(10, 6, $billable_weight, 1, 0, 'C');
            $pdf->Cell(20, 6, substr($notes, 0, 15), 1, 0, 'C');
            $pdf->Cell(10, 6, substr($t->shipment, 0, 12), 1, 0, 'C');
            $pdf->Cell(10, 6, substr($t->receiver, 0, 12), 1, 0, 'C');
            $pdf->Cell(10, 6, substr($t->trailer, 0, 12), 1, 0, 'C');
            $pdf->Cell(22, 6, $t->created_at, 1, 0, 'C');
            $pdf->Ln();
        }
        return $pdf->Output('sample.pdf', 'S');
    }

    private function sortItemsInfo($items): array
    {
        $sortedItems = [];
        foreach ($items as $item) {
            $sortedItems[$this->itemsKey[$item->type]] = $item;
        }
        return $sortedItems;
    }

    private function getTotalTxnItems($transaction): int {
        $totalPcs = 0;
        foreach ($transaction->items as $item) {
            $totalPcs += $item->pcs;
        }
        return $totalPcs;
    }

    private function getTotalTxnWeight($transaction): int {
        $totalWeight = 0;
        foreach ($transaction->items as $item) {
            $totalWeight += ($item->weight * $item->pcs);
        }
        return $totalWeight;
    }

    private function getBillableWeight($transaction, $total_weight = 0): int {
        if ($total_weight == 0) {
            return 0;
        }
        $billable_weight = $transaction['show']['min_carat_weight'];
        $carat_increment = $transaction['show']['carat_weight_inc'];
        while ($billable_weight <= $total_weight) {
            if (empty($carat_increment) || empty($billable_weight) || empty($total_weight)) {
                break;
            }
            $billable_weight += $carat_increment;
        }
        return $billable_weight ?? 0;
    }

    private function getTotalItemInfo(array $transactions): array {

        if (empty($transactions)) {
            return [];
        }

        $totals = [
            'total' => [
                'pcs' => 0,
                'weight' => 0,
                'billable_weight' => 0
            ]
        ];
        foreach($transactions as $transaction) {
            foreach ($transaction['items'] as $item) {
                $type = $this->itemsKey[$item['type']];
                if (!isset($totals[$type])) {
                    $totals[$type] = [
                        'pcs' => 0,
                        'weight' => 0
                    ];
                }
                $pcs = $item['pcs'];
                $weight = $item['weight'] * $pcs;
                $totals[$type]['pcs'] += $pcs;
                $totals[$type]['weight'] += $weight;
                $totals['total']['pcs'] += $pcs;
                $totals['total']['weight'] += $weight;
            }
        }
        foreach ($totals as $type => $info) {
            $total_weight = $info['weight'];
            $totals[$type]['billable_weight'] = $this->getBillableWeight($transactions[0], $total_weight);
        }
        return $totals;
    }

    private function placeItemTotalsTable($pdf, array $totals) {
        $pdf->SetXY( 10, 24);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->MultiCell(25, 10, 'Piece Summary Totals', 1, 'C', false, 1, '', '', true);
        $pdf->SetXY( 35, 24);
        $pdf->Cell(25, 10, 'Total Pcs', 1, 0, 'C');
        $pdf->Cell(25, 10, 'Weight', 1, 0, 'C');
        $pdf->Cell(25, 10, 'Billable Weight', 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(25, 6, 'Crate', 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['crate']['pcs'], 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['crate']['weight'], 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['crate']['billable_weight'], 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(25, 6, 'Carton', 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['carton']['pcs'], 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['carton']['weight'], 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['carton']['billable_weight'], 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(25, 6, 'Skid', 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['skid']['pcs'], 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['skid']['weight'], 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['skid']['billable_weight'], 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(25, 6, 'Fiber Case', 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['fiber_case']['pcs'], 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['fiber_case']['weight'], 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['fiber_case']['billable_weight'], 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(25, 6, 'Carpet', 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['carpet']['pcs'], 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['carpet']['weight'], 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['carpet']['billable_weight'], 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(25, 6, 'Misc', 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['misc']['pcs'], 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['misc']['weight'], 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['misc']['billable_weight'], 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(25, 6, 'Totals', 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['total']['pcs'], 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['total']['weight'], 1, 0, 'C');
        $pdf->Cell(25, 6, $totals['total']['billable_weight'], 1, 0, 'C');
    }

    private function getTotalShipInfo(array $transactions) {
        $totalShipments = [
            'ftl' => 0,
            'ltl' => 0,
            'small_pack' => 0,
        ];
        foreach ($transactions as $transaction) {
            $totalShipments['ltl'] += $transaction['freight_type'] == 1 ? 1 : 0;
            $totalShipments['ftl'] += $transaction['freight_type'] == 2 ? 1 : 0;
            $totalShipments['small_pack'] += $transaction['freight_type'] == 3 ? 1 : 0;
        }
        return $totalShipments;
    }

    private function getTotalUniqueExhibitors($transactions) {
        $uniqueExhibitors = [];
        foreach ($transactions as $transaction) {
            $exhibitorId = $transaction['exhibitor_id'];
            if (!in_array($exhibitorId, $uniqueExhibitors)) {
                $uniqueExhibitors[] = $exhibitorId;
            }
        }
        return count($uniqueExhibitors);
    }

    private function placeTotalShipTable($pdf, array $totalShipments, $totalUniqueExhibitors = 0) {

        $pdf->SetXY( 115, 24);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(40, 10, 'Total Shipments', 1, 0, 'C');
        $pdf->Cell(24, 10, '', 1, 0, 'C');
        $pdf->SetXY( 115, 34);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(40, 6, 'FTL - Full Truck Load', 1, 0, 'C');
        $pdf->Cell(24, 6, $totalShipments['ftl'], 1, 0, 'C');
        $pdf->SetXY( 115, 40);
        $pdf->Cell(40, 6, 'LTL - Less Than Truck Load', 1, 0, 'C');
        $pdf->Cell(24, 6, $totalShipments['ltl'], 1, 0, 'C');
        $pdf->SetXY( 115, 46);
        $pdf->Cell(40, 6, 'Small Pack', 1, 0, 'C');
        $pdf->Cell(24, 6, $totalShipments['small_pack'], 1, 0, 'C');
        $pdf->SetXY( 115, 52);
        $pdf->SetXY( 115, 58);
        $pdf->Cell(40, 6, 'Unique Exhibitors', 1, 0, 'C');
        $pdf->Cell(24, 6, $totalUniqueExhibitors, 1, 0, 'C');
    }

    private function placeTableHeader($pdf) {
        $pdf->SetXY(10, 80);
        $pdf->SetFont('helvetica', 'B', 6);
        $pdf->Cell(4, 6, 'ID', 1, 0, 'C');
        $pdf->Cell(15, 6, 'Shipper', 1, 0, 'C');
        $pdf->Cell(15, 6, 'Exhibitor', 1, 0, 'C');
        $pdf->Cell(10, 6, 'Carrier', 1, 0, 'C');
        $pdf->Cell(15, 6, 'Tracking', 1, 0, 'C');
        $pdf->Cell(15, 6, 'Client', 1, 0, 'C');
        $pdf->Cell(15, 6, 'Shipment', 1, 0, 'C');
        $pdf->Cell(15, 6, 'Zone', 1, 0, 'C');
        $pdf->Cell(10, 6, 'Booth', 1, 0, 'C');
        $pdf->Cell(8, 6, 'Crate', 1, 0, 'C');
        $pdf->Cell(8, 6, 'Carton', 1, 0, 'C');
        $pdf->Cell(8, 6, 'Skid', 1, 0, 'C');
        $pdf->Cell(8, 6, 'Fbr Cse', 1, 0, 'C');
        $pdf->Cell(8, 6, 'Carpet', 1, 0, 'C');
        $pdf->Cell(8, 6, 'Misc', 1, 0, 'C');
        $pdf->Cell(10, 6, 'Total Pcs', 1, 0, 'C');
        $pdf->Cell(10, 6, 'Weight', 1, 0, 'C');
        $pdf->Cell(10, 6, 'Billable', 1, 0, 'C');
        $pdf->Cell(20, 6, 'Remarks / Notes', 1, 0, 'C');
        $pdf->Cell(10, 6, 'Shipment', 1, 0, 'C');
        $pdf->Cell(10, 6, 'Receiver', 1, 0, 'C');
        $pdf->Cell(10, 6, 'Trailer', 1, 0, 'C');
        $pdf->Cell(22, 6, 'Date', 1, 0, 'C');
    }

    private function placeFixedContent($pdf, $transactionsArray): void
    {
        $totals = $this->getTotalItemInfo($transactionsArray);
        $this->placeItemTotalsTable($pdf, $totals);
        $totalShipments = $this->getTotalShipInfo($transactionsArray);
        $totalUniqueExhibitors = $this->getTotalUniqueExhibitors($transactionsArray);
        $this->placeTotalShipTable($pdf, $totalShipments, $totalUniqueExhibitors);
        $this->placeTableHeader($pdf);
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
        $pdf->SetMargins(10, 24, PDF_MARGIN_RIGHT);
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

