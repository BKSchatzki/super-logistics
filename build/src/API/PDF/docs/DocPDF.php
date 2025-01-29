<?php

namespace BigTB\SL\API\PDF\docs;

use TCPDF;

class DocPDF extends TCPDF {

	// Override the Header method to leave it blank
	public function Header() {
		// Do nothing
	}

	// Override the Footer method to leave it blank
	public function Footer() {
		// Do nothing
	}
}