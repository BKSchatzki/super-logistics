<?php

namespace SL\Report\Controllers;

use League\Fractal\Resource\Collection as Collection;
use League\Fractal\Resource\Item as Item;
use SL\Common\Traits\Request_Filter;
use SL\Common\Traits\Transformer_Manager;
use SL\PDF\TrailerManifestGenerator;
use SL\PDF\Transformers\PDFTransformer;
use SL\Transaction\Transformers\TransactionTransformer;
use SL\Transaction\Models\Transaction;
use WP_REST_Request;

class ReportsController
{
    use Transformer_Manager, Request_Filter;

    public function getTrailerManifest(WP_REST_Request $request)
    {
        $trailer_number = $request->get_param('trailerNum');
        $transactions = Transaction::where('trailer', $trailer_number)->get();
        $transactions = new Collection($transactions, new TransactionTransformer);

        $reportGenerator = new TrailerManifestGenerator();
        $pdf = $reportGenerator->generate($transactions);
        // Encode the PDF content to base64
        $pdfBase64 = base64_encode($pdf);

        $res = new Item(['pdf' => $pdfBase64], new PDFTransformer());

        // Return the response
        return $this->get_response($res);
    }
}
