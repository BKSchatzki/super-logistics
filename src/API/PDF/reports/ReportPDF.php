<?php

namespace BigTB\SL\API\PDF\reports;

use TCPDF;

class ReportPDF extends TCPDF
{

    public function __construct()
    {
        parent::__construct('L'); // 'L' for landscape orientation
    }

    // Override the Header method to leave it blank
    public function Header(): void
    {
        // Do nothing
    }

    // Override the Footer method to leave it blank
    public function Footer(): void
    {
        // Set the font
        $this->SetFont('helvetica', 'I', 8);

        // Set the position of the footer
        $this->SetY(-15);

        // Add the page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}