<?php

namespace SL\Reports\Controllers;

use League\Fractal\Resource\Collection as Collection;
use SL\Common\Traits\Request_Filter;
use SL\Common\Traits\Transformer_Manager;
use SL\Reports\Transformers\TransactionTransformer;
use SL\Transaction\Models\Transaction;
use \WP_REST_Request;

class ReportsController
{
    use Transformer_Manager, Request_Filter;

    public function getTrailerManifest(WP_REST_Request $request)
    {
        $trailer_number = $request->get_param('trailer_number');
        $transactions = Transaction::where('trailer', $trailer_number)->get();
        $transactions = new Collection($transactions, new TransactionTransformer);

        return $this->get_response( $transactions );
    }
}
