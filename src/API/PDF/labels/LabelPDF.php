<?php

namespace BigTB\SL\API\PDF\labels;

use TCPDF;

class LabelPDF extends TCPDF {

    // Override the Header method to leave it blank
    public function Header() {
        // Do nothing
    }

    // Override the Footer method to leave it blank
    public function Footer() {
        // Do nothing
    }
}