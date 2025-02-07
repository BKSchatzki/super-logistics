<?php

namespace BigTB\SL\Setup\Routing;

use BigTB\SL\API\Entity\Controllers\ClientController;
use BigTB\SL\API\Package\Controllers\PackageController;
use BigTB\SL\API\Report\Controllers\ReportsController;
use BigTB\SL\API\Entity\Controllers\ShowController;
use BigTB\SL\API\Transaction\Controllers\TransactionController;
use BigTB\SL\API\User\Controllers\UserController;

$basicRoutes = [
	[
		'path'    => '',
		'methods' => [
			[
				'methods'             => 'POST',
				'callback'            => 'create',
				'permission_callback' => 'isLoggedIn'
			],
			[
				'methods'             => 'GET',
				'callback'            => 'get',
				'permission_callback' => '__return_true'
			],
			[
				'methods'             => 'PATCH',
				'callback'            => 'update',
				'permission_callback' => 'isLoggedIn'
			],
			[
				'methods'             => 'DELETE',
				'callback'            => 'delete',
				'permission_callback' => 'isLoggedIn'
			]
		]
	]
];

$statusRoutes = [
	[
		'path' => 'inactive',
		'methods' => [
			[
				'methods'             => 'PATCH',
				'callback'            => 'markInactive',
				'permission_callback' => 'isInternalAdmin'
			]
		]
	],
	[
		'path' => 'active',
		'methods' => [
			[
				'methods'             => 'PATCH',
				'callback'            => 'markActive',
				'permission_callback' => 'isInternalAdmin'
			]
		]
	],
	[
		'path' => 'restore',
		'methods' => [
			[
				'methods'             => 'PATCH',
				'callback'            => 'restore',
				'permission_callback' => 'isInternalAdmin'
			]
		]
	]
];

$entityRoutes = [
	...$basicRoutes,
	...$statusRoutes
];

$clientRouting = [
	'handlerClass' => ClientController::class,
	'base'         => 'clients',
	'routes'       => $entityRoutes
];

$showRouting = [
	'handlerClass' => ShowController::class,
	'base'         => 'shows',
	'routes'       => [
		...$entityRoutes,
		[
			'path'    => 'places',
			'methods' => [
				[
					'methods'             => 'POST',
					'callback'            => 'createPlaces',
					'permission_callback' => 'isAdmin'
				],
				[
					'methods'             => 'PATCH',
					'callback'            => 'updatePlaces',
					'permission_callback' => 'isAdmin'
				],
				[
					'methods'             => 'DELETE',
					'callback'            => 'deletePlaces',
					'permission_callback' => 'isAdmin'
				]
			]
		]
		]
];

$txnRouting = [
	'handlerClass' => TransactionController::class,
	'base'         => 'transactions',
	'routes'       => [
		[
			'path'    => '',
			'methods' => [
				[
					'methods'             => 'POST',
					'callback'            => 'create',
					'permission_callback' => 'isLoggedIn'
				],
				[
					'methods'             => 'GET',
					'callback'            => 'get',
					'permission_callback' => 'isLoggedIn'
				],
				[
					'methods'             => 'DELETE',
					'callback'            => 'delete',
					'permission_callback' => 'isLoggedIn'
				]
			]
		],
		[
			'path' => 'update',
			'methods' => [
				[
					'methods'             => 'POST',
					'callback'            => 'update',
					'permission_callback' => 'isLoggedIn'
				]
			]
		],
		...$statusRoutes,
		[
			'path'    => 'shipping',
			'methods' => [
				[
					'methods'             => 'POST',
					'callback'            => 'printShippingLabels',
					'permission_callback' => '__return_true'
				]
			]
		],
		[
			'path'    => 'receiving/labels',
			'methods' => [
				[
					'methods'             => 'POST',
					'callback'            => 'printAWLabels',
					'permission_callback' => 'isInternal'
				]
			]
		],
		[
			'path'    => 'receiving/docs',
			'methods' => [
				[
					'methods'             => 'POST',
					'callback'            => 'printReceiverDocs',
					'permission_callback' => 'isInternal'
				]
			]
		],
	]
];

$userRouting = [
	'handlerClass' => UserController::class,
	'base'         => 'users',
	'routes'       => [
		[
			'path'    => '',
			'methods' => [
				[
					'methods'             => 'POST',
					'callback'            => 'create',
					'permission_callback' => 'isAdmin'
				],
				[
					'methods'             => 'GET',
					'callback'            => 'get',
					'permission_callback' => 'isAdmin'
				],
				[
					'methods'             => 'PATCH',
					'callback'            => 'update',
					'permission_callback' => 'isAdmin'
				],
				[
					'methods'             => 'DELETE',
					'callback'            => 'delete',
					'permission_callback' => 'isAdmin'
				]
			]
		],
		...$statusRoutes,
		[
			'path'    => 'current',
			'methods' => [
				[
					'methods'             => 'GET',
					'callback'            => 'getCurrent',
					'permission_callback' => 'isLoggedIn'
				]
			]
		],
		[
			'path'    => 'logout',
			'methods' => [
				[
					'methods'             => 'POST',
					'callback'            => 'logout',
					'permission_callback' => 'isLoggedIn'
				]
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
				[
					'methods'             => 'POST',
					'callback'            => 'printTrailerManifest',
					'permission_callback' => 'isLoggedIn'
				]
			]
		],
		[
			'path'    => 'pallet-manifest',
			'methods' => [
				[
					'methods'             => 'POST',
					'callback'            => 'printPalletManifest',
					'permission_callback' => 'isLoggedIn'
				]
			]
		],
		[
			'path'    => 'show-report',
			'methods' => [
				[
					'methods'             => 'POST',
					'callback'            => 'printShowReport',
					'permission_callback' => 'isLoggedIn'
				]
			],
		]
	]
];

return [
	$clientRouting,
	$showRouting,
	$txnRouting,
	$userRouting,
	$reportRouting
];
