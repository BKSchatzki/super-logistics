<?php

namespace BigTB\SL\API\Report\Controllers;

use League\Fractal\Resource\Collection as Collection;
use League\Fractal\Resource\Item as Item;
use BigTB\SL\API\Common\Traits\Request_Filter;
use BigTB\SL\API\Setup\ResponseManager;
use BigTB\SL\API\PDF\PalletManifestGenerator;
use BigTB\SL\API\PDF\TrailerManifestGenerator;
use BigTB\SL\API\PDF\ShowReportGenerator;
use BigTB\SL\API\PDF\ShowReportGeneratorTwo;
use BigTB\SL\API\PDF\Transformers\PDFTransformer;
use BigTB\SL\API\Transaction\Transformers\TransactionTransformer;
use BigTB\SL\API\Transaction\Models\Transaction;
use WP_REST_Request;

class ReportsController
{
    use ResponseManager;

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
        return $this->prepareArrayResponse($res);
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
        return $this->prepareArrayResponse($res);
    }

    public function getShowReport(WP_REST_Request $request)
    {
        $client_id = $request->get_param('client_id');
        $show_id = $request->get_param('show_id');
        $start_date = $request->get_param('start_date');
        $end_date = $request->get_param('end_date');
        $transactions = Transaction::with([
            'client', 'show.entity', 'carrier', 'shipper', 'exhibitor', 'showPlace', 'items',
                'updates' => function($query) {
                    $query->orderBy('datetime', 'desc');
                }, 'updates.user'
            ])
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
        return $this->prepareArrayResponse($res);
    }

    public function getShowReportTwo(WP_REST_Request $request)
    {
        $client_id = $request->get_param('client_id');
        $show_id = $request->get_param('show_id');
        $start_date = $request->get_param('start_date');
        $end_date = $request->get_param('end_date');
        $transactions = Transaction::with([
            'client', 'show.entity', 'carrier', 'shipper', 'exhibitor', 'showPlace', 'items',
                'updates' => function($query) {
                    $query->orderBy('datetime', 'desc');
                }, 'updates.user'
            ])
            ->where('client_id', $client_id)
            ->where('show_id', $show_id)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->get();

        $reportGenerator = new ShowReportGeneratorTwo();
        $pdf = $reportGenerator->generate($transactions, $start_date, $end_date);
        // Encode the PDF content to base64
        $pdfBase64 = base64_encode($pdf);

        $res = new Item(['pdf' => $pdfBase64], new PDFTransformer());

        // Return the response
        return $this->prepareArrayResponse($res);
    }
}
