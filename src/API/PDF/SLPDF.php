<?php
namespace BigTB\SL\API\PDF;
use TCPDF;

class ReportPDF extends TCPDF {
    private $trailer_manifest = false;
    private $pallet_manifest = false;
    private $show_report = false;
    private $detail = '';

    public function __construct($orientation = 'P', $unit = 'mm', $format = array(102, 152), $report = null, $detail = '',$unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

        switch ($report) {
            case 'trailer_manifest':
                $this->trailer_manifest = true;
                break;
            case 'pallet_manifest':
                $this->pallet_manifest = true;
                break;
            case 'show_report':
                $this->show_report = true;
                break;
            default:
                break;
        }
        $this->detail = $detail;
    }

    public function Header() {
        if ($this->trailer_manifest) {
            $this->SetXY(10, 10);
            $this->SetFont('helvetica', 'B', 40);
            $this->Cell(136, 10, ' Trailer Manifest', 1);
            $this->Cell(64, 10, ' ' . $this->detail, 1);
            $this->Ln();

            $this->SetXY(10, 30);
            $this->SetFont('helvetica', 'B', 8);
            $this->Cell(15, 6, 'Rec ID', 1, 0, 'C');
            $this->Cell(25, 6, 'Exhibitor', 1, 0, 'C');
            $this->Cell(15, 6, 'Carrier', 1, 0, 'C');
            $this->Cell(30, 6, 'Tracking', 1, 0, 'C');
            $this->Cell(25, 6, 'Zone', 1, 0, 'C');
            $this->Cell(15, 6, 'Booth', 1, 0, 'C');
            $this->Cell(15, 6, 'Tot. Pcs', 1, 0, 'C');
            $this->Cell(45, 6, 'Remarks / Notes ???', 1, 0, 'C');
            $this->Cell(25, 6, 'Shipment', 1, 0, 'C');
            $this->Cell(15, 6, 'Trailer', 1, 0, 'C');
            $this->Cell(25, 6, 'Date Received', 1, 0, 'C');
            $this->Ln();
        }
        if ($this->pallet_manifest) {
            $this->SetXY(10, 10);
            $this->SetFont('helvetica', 'B', 40);
            $this->Cell(136, 10, ' Pallet Manifest', 1);
            $this->Cell(64, 10, ' ' . $this->detail, 1);
            $this->Ln();

            $this->SetXY(10, 30);
            $this->SetFont('helvetica', 'B', 8);
            $this->Cell(15, 6, 'Rec ID', 1, 0, 'C');
            $this->Cell(25, 6, 'Exhibitor', 1, 0, 'C');
            $this->Cell(15, 6, 'Carrier', 1, 0, 'C');
            $this->Cell(30, 6, 'Tracking', 1, 0, 'C');
            $this->Cell(25, 6, 'Zone', 1, 0, 'C');
            $this->Cell(15, 6, 'Booth', 1, 0, 'C');
            $this->Cell(15, 6, 'Tot Pcs', 1, 0, 'C');
            $this->Cell(45, 6, 'Remarks / Notes ???', 1, 0, 'C');
            $this->Cell(25, 6, 'Shipment', 1, 0, 'C');
            $this->Cell(15, 6, 'Pallet', 1, 0, 'C');
            $this->Cell(15, 6, 'Trailer', 1, 0, 'C');
            $this->Cell(25, 6, 'Date Received', 1, 0, 'C');
            $this->Ln();
        }
        if ($this->show_report) {
            $this->SetXY(10, 10);
            $this->SetFont('helvetica', 'B', 10);
            $this->Cell(32, 10, 'Show Report', 1, 0, 'C');
            $this->Cell(52, 10, $this->detail['show_name'], 1, 0, 'C');
            $this->Cell(32, 10, $this->detail['start_date'], 1, 0, 'C');
            $this->Cell(32, 10, $this->detail['end_date'], 1, 0, 'C');
            $this->Ln();
        }
    }

    public function Footer() {
        // Leave this method blank to remove the footer content including page numbers
    }
}
