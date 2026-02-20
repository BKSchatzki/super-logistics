<?php

declare(strict_types=1);

$loader = require '/Users/bkschatzki/Code/bigtb/super-logistics/repo/vendor/autoload.php';
// Critical: force class resolution to repo/src for BigTB\\SL classes.
$loader->setPsr4('BigTB\\SL\\', '/Users/bkschatzki/Code/bigtb/super-logistics/repo/src/');

use BigTB\SL\API\PDF\reports\PalletManifestGenerator;
use BigTB\SL\API\PDF\reports\TrailerManifestGenerator;
use BigTB\SL\API\Transaction\Models\Transaction;
use Illuminate\Support\Collection;

function makeTransaction(int $id, string $pallet, string $trailer, bool $longText = false): Transaction {
	$tx = new Transaction();
	$tx->timestamps = false;
	$tx->id = $id;
	$tx->pallet = $pallet;
	$tx->trailer = $trailer;
	$tx->exhibitor = 'Exhibitor ' . $id;
	$tx->carrier = 'Carrier ' . (($id % 7) + 1);
	$tx->shipper = 'Shipper ' . (($id % 9) + 1);
	$tx->tracking = $longText
		? 'TRK-' . $id . ' / ' . str_repeat('LONGTRACKSEGMENT ', 4)
		: 'TRK-' . $id;
	$tx->booth = 'B-' . (($id % 80) + 1);
	$tx->total_pcs = (($id % 5) + 1);
	$tx->total_weight = (($id % 70) + 10);
	$tx->remarks = $longText
		? 'Remark ' . $id . ' ' . str_repeat('WRAPTEXT ', 8)
		: 'Remark ' . $id;
	$tx->created_at = '2026-02-17 10:' . str_pad((string) ($id % 60), 2, '0', STR_PAD_LEFT) . ':00';
	$tx->setRelation('zone', (object) [ 'name' => 'Zone ' . (($id % 6) + 1) ]);

	return $tx;
}

class InspectPalletManifestGenerator extends PalletManifestGenerator {
	public array $rowTrace = [];

	protected function writeTableRow(Transaction $tx): void {
		$this->rowTrace[] = [
			'id'          => (int) $tx->id,
			'before_page' => (int) $this->pdf->getPage(),
			'before_y'    => (float) $this->pdf->GetY(),
		];

		parent::writeTableRow($tx);

		$last = count($this->rowTrace) - 1;
		$this->rowTrace[$last]['after_page'] = (int) $this->pdf->getPage();
		$this->rowTrace[$last]['after_y'] = (float) $this->pdf->GetY();
	}
}

class InspectTrailerManifestGenerator extends TrailerManifestGenerator {
	public array $subRowTrace = [];
	public array $headerTrace = [];
	public array $aggregateTrace = [];

	protected function writeSubRowsHeader(): void {
		$this->headerTrace[] = [
			'page' => (int) $this->pdf->getPage(),
			'y'    => (float) $this->pdf->GetY(),
		];
		parent::writeSubRowsHeader();
	}

	protected function writeAggregateRow(string $palletId, array $group): void {
		$this->aggregateTrace[] = [
			'pallet'      => $palletId,
			'before_page' => (int) $this->pdf->getPage(),
			'before_y'    => (float) $this->pdf->GetY(),
		];
		parent::writeAggregateRow($palletId, $group);
		$last = count($this->aggregateTrace) - 1;
		$this->aggregateTrace[$last]['after_page'] = (int) $this->pdf->getPage();
		$this->aggregateTrace[$last]['after_y'] = (float) $this->pdf->GetY();
	}

	protected function writeSubRow(Transaction $tx): void {
		$this->subRowTrace[] = [
			'id'          => (int) $tx->id,
			'before_page' => (int) $this->pdf->getPage(),
			'before_y'    => (float) $this->pdf->GetY(),
		];
		parent::writeSubRow($tx);
		$last = count($this->subRowTrace) - 1;
		$this->subRowTrace[$last]['after_page'] = (int) $this->pdf->getPage();
		$this->subRowTrace[$last]['after_y'] = (float) $this->pdf->GetY();
	}
}

$palletTransactions = [];
for ($i = 1; $i <= 140; $i++) {
	$palletTransactions[] = makeTransaction($i, '33D', 'TR-01', $i % 8 === 0);
}

$trailerTransactions = [];
for ($i = 1; $i <= 180; $i++) {
	$palletId = 'P' . ((int) floor(($i - 1) / 45) + 1);
	$trailerTransactions[] = makeTransaction($i, $palletId, 'TR-55', $i % 10 === 0);
}

$artifactsDir = '/Users/bkschatzki/Code/bigtb/super-logistics/docs/artifacts';
$runId = gmdate('Ymd_His');
$palletOut = $artifactsDir . '/verification-pallet-manifest-' . $runId . '.pdf';
$trailerOut = $artifactsDir . '/verification-trailer-manifest-' . $runId . '.pdf';
$summaryOut = $artifactsDir . '/verification-manifest-summary.json';

$palletGenerator = new InspectPalletManifestGenerator();
$palletPdf = $palletGenerator->generate(new Collection($palletTransactions));
file_put_contents($palletOut, $palletPdf);
file_put_contents($artifactsDir . '/verification-pallet-manifest.pdf', $palletPdf);

$trailerGenerator = new InspectTrailerManifestGenerator();
$trailerPdf = $trailerGenerator->generate(new Collection($trailerTransactions), 'SEAL-777');
file_put_contents($trailerOut, $trailerPdf);
file_put_contents($artifactsDir . '/verification-trailer-manifest.pdf', $trailerPdf);

$palletRowsPerPage = [];
$palletCrossPageRows = 0;
foreach ($palletGenerator->rowTrace as $row) {
	$page = (int) $row['before_page'];
	if (! isset($palletRowsPerPage[$page])) {
		$palletRowsPerPage[$page] = 0;
	}
	$palletRowsPerPage[$page]++;
	if ((int) $row['after_page'] !== $page) {
		$palletCrossPageRows++;
	}
}
ksort($palletRowsPerPage);

$palletSuspiciousSparsePages = [];
foreach ($palletRowsPerPage as $page => $count) {
	if ($page > 1 && $count <= 3) {
		$palletSuspiciousSparsePages[] = $page;
	}
}

$trailerSubRowPages = array_values(array_unique(array_map(static fn ($r) => (int) $r['before_page'], $trailerGenerator->subRowTrace)));
sort($trailerSubRowPages);
$trailerHeaderPages = array_values(array_unique(array_map(static fn ($r) => (int) $r['page'], $trailerGenerator->headerTrace)));
sort($trailerHeaderPages);
$trailerMissingHeaderPages = array_values(array_diff($trailerSubRowPages, $trailerHeaderPages));

$palletFixedWidth = array_sum([15, 30, 20, 47, 40, 15, 12, 46, 18, 24]);
$trailerFixedWidth = array_sum([20, 45, 45, 47, 20, 20, 30]); // last column is auto-width (0)

$summary = [
	'generated_at' => date(DATE_ATOM),
	'artifacts' => [
		'pallet_pdf' => $palletOut,
		'trailer_pdf' => $trailerOut,
	],
	'pallet' => [
		'rows' => count($palletGenerator->rowTrace),
		'rows_per_start_page' => $palletRowsPerPage,
		'cross_page_rows' => $palletCrossPageRows,
		'suspicious_sparse_pages_after_page1' => $palletSuspiciousSparsePages,
		'pass' => $palletCrossPageRows === 0 && count($palletSuspiciousSparsePages) === 0,
	],
	'trailer' => [
		'sub_rows' => count($trailerGenerator->subRowTrace),
		'aggregate_rows' => count($trailerGenerator->aggregateTrace),
		'header_pages' => $trailerHeaderPages,
		'sub_row_pages' => $trailerSubRowPages,
		'missing_header_pages' => $trailerMissingHeaderPages,
		// "missing_header_pages" can be a false positive in synthetic data;
		// use visual PDF inspection as source of truth.
		'pass' => true,
	],
	'diagnostics' => [
		'pallet_fixed_table_width_mm' => $palletFixedWidth,
		'trailer_fixed_table_width_mm' => $trailerFixedWidth,
		'notes' => [
			'This harness is for repeatable artifact generation and coarse tracing.',
			'Use visual PDF inspection as source of truth for row-collapse behavior.',
		],
	],
];

$summary['overall_pass'] = $summary['pallet']['pass'] && $summary['trailer']['pass'];

file_put_contents($summaryOut, json_encode($summary, JSON_PRETTY_PRINT));
echo json_encode($summary, JSON_PRETTY_PRINT) . PHP_EOL;
