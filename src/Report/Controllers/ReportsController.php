<?php

namespace SL\Report\Controllers;

use League\Fractal\Resource\Collection as Collection;
use League\Fractal\Resource\Item as Item;
use SL\Common\Traits\Request_Filter;
use SL\Common\Traits\Transformer_Manager;
use SL\PDF\PalletManifestGenerator;
use SL\PDF\TrailerManifestGenerator;
use SL\PDF\ShowReportGenerator;
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
        $transactions = Transaction::with(
            ['client', 'carrier', 'shipper', 'exhibitor', 'showPlace', 'items', 'updates'])
            ->where('trailer', $trailer_number)
            ->get();

        $reportGenerator = new TrailerManifestGenerator();
        $pdf = $reportGenerator->generate($transactions);
        // Encode the PDF content to base64
        $pdfBase64 = base64_encode($pdf);

        $res = new Item(['pdf' => $pdfBase64], new PDFTransformer());

        // Return the response
        return $this->get_response($res);
    }

    public function getPalletManifest(WP_REST_Request $request)
    {
        $pallet_number = $request->get_param('palletNum');
        $transactions = Transaction::with(
            ['client', 'carrier', 'shipper', 'exhibitor', 'showPlace', 'items', 'updates'])
            ->where('pallet_no', $pallet_number)
            ->get();

        $reportGenerator = new PalletManifestGenerator();
        $pdf = $reportGenerator->generate($transactions);
        // Encode the PDF content to base64
        $pdfBase64 = base64_encode($pdf);

        $res = new Item(['pdf' => $pdfBase64], new PDFTransformer());

        // Return the response
        return $this->get_response($res);
    }

    public function getShowReport(WP_REST_Request $request)
    {
        $client_id = $request->get_param('client_id');
        $show_id = $request->get_param('show_id');
        $start_date = $request->get_param('start_date');
        $end_date = $request->get_param('end_date');
        $transactions = Transaction::with(
            ['client', 'show.entity', 'carrier', 'shipper', 'exhibitor', 'showPlace', 'items', 'updates'])
            ->where('client_id', $client_id)
            ->where('show_id', $show_id)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->get();

        $reportGenerator = new ShowReportGenerator();
        $pdf = $reportGenerator->generate($transactions, $start_date, $end_date);
        // Encode the PDF content to base64
        $pdfBase64 = base64_encode($pdf);

        $res = new Item(['pdf' => $pdfBase64], new PDFTransformer());

        // Return the response
        return $this->get_response($res);
    }
}
