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
				'permission_callback' => 'isLoggedIn'
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

$entityRoutes = [
	...$basicRoutes,
	[
		'path' => 'status',
		'methods' => [
			[
				'methods'             => 'PATCH',
				'callback'            => 'markInactive',
				'permission_callback' => 'isLoggedIn'
			]
		]
	]
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
					'permission_callback' => 'isLoggedIn'
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
				],
			]
		],
		[
			'path' => 'status',
			'methods' => [
				[
					'methods'             => 'PATCH',
					'callback'            => 'markInactive',
					'permission_callback' => 'isInternalAdmin'
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
		],
		[
			'path' => 'status',
			'methods' => [
				[
					'methods'             => 'PATCH',
					'callback'            => 'markInactive',
					'permission_callback' => 'isInternalAdmin'
				]
			]
		],
		[
			'path'    => 'labels',
			'methods' => [
				[
					'methods'             => 'GET',
					'callback'            => 'getLabels',
					'permission_callback' => 'isLoggedIn'
				]
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
				[
					'methods'             => 'GET',
					'callback'            => 'getLabels',
					'permission_callback' => 'isLoggedIn'
				]
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
					'methods'             => 'GET',
					'callback'            => 'getTrailerManifest',
					'permission_callback' => 'isLoggedIn'
				]
			]
		],
		[
			'path'    => 'pallet-manifest',
			'methods' => [
				[
					'methods'             => 'GET',
					'callback'            => 'getPalletManifest',
					'permission_callback' => 'isLoggedIn'
				]
			]
		],
		[
			'path'    => 'show-report',
			'methods' => [
				[
					'methods'             => 'GET',
					'callback'            => 'getShowReport',
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
	$itemRouting,
	$noteRouting,
	$userRouting,
	$reportRouting
];
