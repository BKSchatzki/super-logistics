<?php
namespace SL\PDF;
use TCPDF;

class SLPDF extends TCPDF {
    private $trailer_manifest = false;
    private $pallet_manifest = false;
    private $detail = '';

    public function __construct($orientation = 'P', $unit = 'mm', $format = array(102, 152), $report = null, $detail = '',$unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

        if ($report === 'trailer_manifest') {
            $this->trailer_manifest = true;
        }
        if ($report === 'pallet_manifest') {
            $this->pallet_manifest = true;
        }
        $this->detail = $detail;
    }

    public function Header() {
        if ($this->trailer_manifest) {
            $this->SetXY(10, 10);
            $this->SetFont('helvetica', 'B', 40);
            $this->Cell(136, 10, 'Trailer Manifest', 1);
            $this->Cell(64, 10, $this->detail, 1);
            $this->Ln();

            $this->SetXY(10, 30);
            $this->SetFont('helvetica', 'B', 8);
            $this->Cell(15, 6, 'Rec ID', 1);
            $this->Cell(25, 6, 'Exhibitor', 1);
            $this->Cell(25, 6, 'Carrier', 1);
            $this->Cell(25, 6, 'Tracking', 1);
            $this->Cell(25, 6, 'Zone', 1);
            $this->Cell(20, 6, 'Total Pcs', 1);
            $this->Cell(35, 6, 'Remarks / Notes ???', 1);
            $this->Cell(25, 6, 'Shipment', 1);
            $this->Cell(25, 6, 'Trailer', 1);
            $this->Cell(25, 6, 'Date Received', 1);
            $this->Ln();
        }
        if ($this->pallet_manifest) {
            $this->SetXY(10, 10);
            $this->SetFont('helvetica', 'B', 40);
            $this->Cell(136, 10, 'Pallet Manifest', 1);
            $this->Cell(64, 10, $this->detail, 1);
            $this->Ln();

            $this->SetXY(10, 30);
            $this->SetFont('helvetica', 'B', 8);
            $this->Cell(15, 6, 'Rec ID', 1);
            $this->Cell(25, 6, 'Exhibitor', 1);
            $this->Cell(25, 6, 'Carrier', 1);
            $this->Cell(25, 6, 'Tracking', 1);
            $this->Cell(25, 6, 'Zone', 1);
            $this->Cell(20, 6, 'Total Pcs', 1);
            $this->Cell(35, 6, 'Remarks / Notes ???', 1);
            $this->Cell(25, 6, 'Shipment', 1);
            $this->Cell(20, 6, 'Pallet', 1);
            $this->Cell(20, 6, 'Trailer', 1);
            $this->Cell(25, 6, 'Date Received', 1);
            $this->Ln();
        }
    }

    public function Footer() {
        // Leave this method blank to remove the footer content including page numbers
    }
}
