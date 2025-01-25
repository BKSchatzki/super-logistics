<?php


namespace BigTB\SL\API\Transaction\Transformers;

use BigTB\SL\API\Transaction\Models\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract {

	public function transform( Transaction $item ): array {
		$show = [
			'id'   => $item->show->id,
			'name' => $item->show->name,
		];

		return [
			'id'      => (int) $item->id,
			'name'    => (string) $item->name,
			'show'    => $show,
			'updates' => $item->updates ?? [],
			'zone'    => (string) $item->zone,
			'active'  => (bool) $item->active
		];
	}
}
