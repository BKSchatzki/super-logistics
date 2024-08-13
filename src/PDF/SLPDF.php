<?php
namespace SL\PDF;
use TCPDF;

class SLPDF extends TCPDF {
    public function __construct($orientation = 'P', $unit = 'mm', $format = array(102, 152), $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
    }

    public function Header() {
        //blank header for now
    }

    public function Footer() {
        // Leave this method blank to remove the footer content including page numbers
    }
}
