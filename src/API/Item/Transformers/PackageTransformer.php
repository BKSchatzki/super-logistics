<?php

namespace BigTB\SL\API\Package\Transformers;
use BigTB\SL\API\Package\Models\Package;
use League\Fractal\TransformerAbstract;

class PackageTransformer extends TransformerAbstract {

	public function transform( Package $item ): array {
		return [
			'id' => $item->id,
			'transaction_id' => $item->transaction_id,
			'type' => $item->type,
			'pcs' => $item->pcs,
			'bol_count' => $item->bol_count,
			'weight' => $item->weight,
			'special' => $item->special,
			'notes' => $item->notes,
			'tracking' => $item->tracking,
			'carrier' => $item->carrier,
			'exhibitor' => $item->exhibitor,
			'returning' => $item->returning,
			'active' => $item->active
		];
	}
}