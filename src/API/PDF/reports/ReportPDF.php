<?php

namespace BigTB\SL\API\PDF\reports;

use TCPDF;

class ReportPDF extends TCPDF {

    public function __construct() {
        parent::__construct('L'); // 'L' for landscape orientation
    }

    // Override the Header method to leave it blank
    public function Header() {
        // Do nothing
    }

    // Override the Footer method to leave it blank
    public function Footer() {
        // Do nothing
    }
}