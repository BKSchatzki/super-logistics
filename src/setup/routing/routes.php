<?php

namespace BigTB\SL\Setup\Routing;

use BigTB\SL\API\Entity\Controllers\EntityController;
use BigTB\SL\API\Package\Controllers\PackageController;
use BigTB\SL\API\Report\Controllers\ReportsController;
use BigTB\SL\API\Show\Controllers\ShowController;
use BigTB\SL\API\Transaction\Controllers\TransactionController;
use BigTB\SL\API\User\Controllers\UserController;

$basicRoutes = [
	[
		'path'    => '',
		'methods' => [
			[ 'methods' => 'POST', 'callback' => 'create', 'permission_callback' => '__return_true' ],
			[ 'methods' => 'GET', 'callback' => 'get', 'permission_callback' => '__return_true' ],
			[ 'methods' => 'PATCH', 'callback' => 'update', 'permission_callback' => '__return_true' ],
			[ 'methods' => 'DELETE', 'callback' => 'delete', 'permission_callback' => '__return_true' ]
		]
	]
];

$entityRoutes = [
	...$basicRoutes,
	[
		'path'    => 'inactivate',
		'methods' => [
			[ 'methods' => 'PATCH', 'callback' => 'inactivate', 'permission_callback' => '__return_true' ]
		]
	]
];

$clientRouting = [
	'handlerClass' => EntityController::class,
	'base'         => 'clients',
	'routes'       => $entityRoutes
];

$showRouting = [
	'handlerClass' => ShowController::class,
	'base'         => 'shows',
	'routes'       => $entityRoutes
];

$carrierRouting = [
	'handlerClass' => EntityController::class,
	'base'         => 'carriers',
	'routes'       => $entityRoutes
];

$txnRouting = [
	'handlerClass' => TransactionController::class,
	'base'         => 'transactions',
	'routes'       => [
		...$entityRoutes,
		[
			'path'    => 'labels',
			'methods' => [
				[ 'methods' => 'GET', 'callback' => 'getLabels' ]
			]
		],
	]
];

$itemRouting = [
	'handlerClass' => PackageController::class,
	'base'         => 'packages',
	'routes'       => [
		...$basicRoutes,
		[
			'path'    => 'labels',
			'methods' => [
				[ 'methods' => 'GET', 'callback' => 'getLabels' ]
			]
		],
	]
];

$noteRouting = [
	'handlerClass' => TransactionController::class,
	'base'         => 'transactions/notes',
	'routes'       => $basicRoutes
];

$userRouting = [
	'handlerClass' => UserController::class,
	'base'         => 'users',
	'routes'       => [
		...$basicRoutes,
		[
			'path'    => 'current',
			'methods' => [
				[ 'methods' => 'GET', 'callback' => 'getCurrent' ]
			]
		]
	]
];

$reportRouting = [
	'handlerClass' => ReportsController::class,
	'base'         => 'reports',
	'routes'       => [
		[
			'path'    => 'trailer-manifest',
			'methods' => [
				[ 'methods' => 'GET', 'callback' => 'getTrailerManifest' ]
			]
		],
		[
			'path'    => 'pallet-manifest',
			'methods' => [
				[ 'methods' => 'GET', 'callback' => 'getPalletManifest' ]
			]
		],
		[
			'path'    => 'show-report',
			'methods' => [
				[ 'methods' => 'GET', 'callback' => 'getShowReport' ]
			]
		],
	]
];

return [
	$clientRouting,
	$showRouting,
	$carrierRouting,
	$txnRouting,
	$itemRouting,
	$noteRouting,
	$userRouting,
	$reportRouting
];
