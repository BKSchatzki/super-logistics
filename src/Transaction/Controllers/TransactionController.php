<?php

namespace SL\Transaction\Controllers;

use SL\Transaction\Models\Transaction;
use WP_REST_Request;
use League\Fractal\Resource\Item as Item;
use League\Fractal\Resource\Collection as Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use SL\Common\Traits\Transformer_Manager;
use SL\Common\Traits\Request_Filter;
use SL\User\Models\User;
use SL\User\Transformers\User_Transformer;
use Illuminate\Pagination\Paginator;
use SL\Transaction\Transformers\TransactionTransformer;

class TransactionController {
    use Transformer_Manager, Request_Filter;

    public function show( WP_REST_Request $request ): array {
        $transactions = Transaction::all();
        $resource = new Collection($transactions, new TransactionTransformer);

        return $this->get_response( $resource );
    }

    public function store( WP_REST_Request $request ): array {
        $transaction_data = json_decode($request->get_param( 'transaction' ), true);
        $items_data = json_decode($request->get_param( 'items' ), true);

        $transaction = Transaction::create($transaction_data);
        foreach ($items_data as $data) {
            $transaction->items()->create($data);
        }

        $transaction->save();
        $resource = new Item($transaction, new TransactionTransformer);

        return $this->get_response( $resource );
    }
}
