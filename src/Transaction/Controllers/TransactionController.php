<?php

namespace SL\Transaction\Controllers;

use SL\Transaction\Models\Transaction;
use WP_REST_Request;
use League\Fractal\Resource\Item as Item;
use League\Fractal\Resource\Collection as Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use SL\Common\Traits\Transformer_Manager;
use SL\Common\Traits\Request_Filter;
use SL\Transaction\Transformers\TransactionTransformer;
use SL\PDF\LabelGenerator;
use SL\PDF\Transformers\LabelTransformer;
use SL\Update\Models\Update;

class TransactionController {
    use Transformer_Manager, Request_Filter;

    public function index( WP_REST_Request $request ): array {
        $transactions = Transaction::paginate( $this->get_per_page() );
        $resource = new Collection($transactions, new TransactionTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($transactions));

        return $this->get_response( $resource );
    }

    public function get( WP_REST_Request $request ): array {
        $transaction = Transaction::with(['show', 'client', 'carrier', 'shipper', 'exhibitor', 'updates', 'items'])->find($request->get_param('id'));
        $resource = new Item($transaction, new TransactionTransformer);

        return $this->get_response( $resource );
    }

    public function store(WP_REST_Request $request): array {
        $transaction_data = json_decode($request->get_param('transaction'), true);
        $items_data = $transaction_data['items'];

        $update_data = [
            'user_id' => get_current_user_id(),
            'type' => 1,
            'note' => $transaction_data['note'],
            'datetime' => date('Y-m-d H:i:s'),
        ];

        if (isset($_FILES['image'])) {
            $update_data['image_path'] = self::handleImageUpload($_FILES['image']);
        } else {
            $update_data['image_path'] = '';
        }

        $transaction = Transaction::create($transaction_data);
        foreach ($items_data as $data) {
            $transaction->items()->create($data);
        }
        $transaction->updates()->create($update_data);

        $transaction->save();
        $resource = new Item($transaction, new TransactionTransformer);

        return $this->get_response($resource);
    }

    public function update(WP_REST_Request $request): array {
        $transaction_data = json_decode($request->get_param('transaction'), true);

        $items_data = $transaction_data['items'];

        $update_data = [
            'user_id' => get_current_user_id(),
            'type' => 1,
            'note' => $transaction_data['note'],
            'datetime' => date('Y-m-d H:i:s'),
        ];

        if (isset($_FILES['image'])) {
            $update_data['image_path'] = self::handleImageUpload($_FILES['image']);
        } else {
            $update_data['image_path'] = '';
        }

        $transaction = Transaction::find($transaction_data['id']);
        $transaction->fill($transaction_data);

        foreach ($items_data as $data) {
            $item_array = $transaction->items()->where('type', $data['type'])->get();
            $item = $item_array->first();
            if ($item) {
                $item->fill($data);
                $item->save();
            }
        }
        $transaction->updates()->create($update_data);

        $transaction->save();
        $resource = new Item($transaction, new TransactionTransformer);

        return $this->get_response($resource);
    }

    public function delete(WP_REST_Request $request): array {
        $transaction = Transaction::find($request->get_param('id'));
        $transaction->delete();

        return $this->get_response([]);
    }

    public function search(WP_REST_Request $request): array {

        $totalQueries = [
            'show_id' => $request->get_param('show_id'),
            'client_id' => $request->get_param('client_id'),
            'carrier_id' => $request->get_param('carrier_id'),
            'shipper_id' => $request->get_param('shipper_id'),
            'exhibitor_id' => $request->get_param('exhibitor_id'),
            'shipment' => $request->get_param('shipment'),
            'place' => $request->get_param('place'),
            'billable_weight' => $request->get_param('billable_weight'),
            'pallet_no' => $request->get_param('pallet_no'),
            'freight_type' => $request->get_param('freight_type')
        ];

        // Start a new query
        $query = Transaction::query();

        // Add where clauses for each non-empty parameter
        foreach ($totalQueries as $field => $value) {
            if ($value !== '' && $value !== null) {
                $query->where($field, $value);
            }
        }

        $transactions = $query->with(['show', 'client', 'carrier', 'shipper', 'exhibitor', 'updates', 'items'])->get();
        $resource = new Collection($transactions, new TransactionTransformer);
        return $this->get_response($resource);
    }

    public function createLabels(WP_REST_Request $request): array
    {
        $trans_id = $request->get_param('trans_id');
        $t = Transaction::with(['show.entity', 'client', 'carrier', 'shipper', 'exhibitor', 'showPlace', 'items'])->find($trans_id);

        // Create a new LabelGenerator and generate the PDF content
        $labelGenerator = new LabelGenerator();
        $pdf = $labelGenerator->generate($t);
        // Encode the PDF content to base64
        $pdfBase64 = base64_encode($pdf);

        $res = new Item(['pdf' => $pdfBase64], new LabelTransformer());

        // Return the response
        return $this->get_response($res);
    }

    public function storeNote(WP_REST_Request $request): array {
        $transaction_id = $request->get_param('transaction_id');
        $note = $request->get_param('note');

        $update_data = [
            'user_id' => get_current_user_id(),
            'type' => 1,
            'note' => $note,
            'datetime' => date('Y-m-d H:i:s'),
        ];

        $transaction = Transaction::find($transaction_id);
        $transaction->updates()->create($update_data);

        $resource = new Item($transaction, new TransactionTransformer);

        return $this->get_response($resource);
    }

    private static function handleImageUpload($file): string {
        // Define overrides
        $overrides = array(
            'test_form' => false,
            'mimes' => array(
                'jpg|jpeg|jpe' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif'
            )
        );

        // Handle file upload
        $upload = wp_handle_upload($file, $overrides);

        // Check for errors
        if (isset($upload['error'])) {
            return new WP_Error('upload_failed', $upload['error'], array('status' => 500));
        }

        // File upload successful, get file URL
        $upload_dir = wp_upload_dir();
        $url = $upload_dir['url'] . '/' . basename($upload['file']);
        $base_url = trailingslashit($upload_dir['baseurl']);
        return 'wp-content/uploads/' . str_replace($base_url, '', $url);
    }

    public function removeNote(WP_REST_Request $request): array {
        $update_id = $request->get_param('update_id');
        $update = Update::find($update_id);
        $update->update(['note' => '']);
        return $this->get_response([]);
    }
}
