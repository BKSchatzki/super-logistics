<?php

namespace BigTB\SL\Setup\Routing;

use BigTB\SL\API\Entity\Controllers\ClientController;
use BigTB\SL\API\Entity\Controllers\ShowController;
use BigTB\SL\API\Report\Controllers\ReportsController;
use BigTB\SL\API\Transaction\Controllers\TransactionController;
use BigTB\SL\API\User\Controllers\UserController;

class RouteConfig {

	public array $routes = [];
	public array $entityRoutes = [];
	public array $clientRoutes = [];
	public array $showRoutes = [];
	public array $txnRoutes = [];
	public array $userRoutes = [];
	public array $reportRoutes = [];

	public array $basicRoutes = [
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
	public array $statusRoutes = [
		[
			'path'    => 'inactive',
			'methods' => [
				[
					'methods'             => 'PATCH',
					'callback'            => 'markInactive',
					'permission_callback' => 'isInternalAdmin'
				]
			]
		],
		[
			'path'    => 'active',
			'methods' => [
				[
					'methods'             => 'PATCH',
					'callback'            => 'markActive',
					'permission_callback' => 'isInternalAdmin'
				]
			]
		],
		[
			'path'    => 'restore',
			'methods' => [
				[
					'methods'             => 'PATCH',
					'callback'            => 'restore',
					'permission_callback' => 'isInternalAdmin'
				]
			]
		]
	];

	public function __construct() {

		$this->writeEntityRoutes();
		$this->writeClientRoutes();
		$this->writeShowRoutes();
		$this->writeTxnRoutes();
		$this->writeUserRoutes();
		$this->writeReportRoutes();

		$this->routes = [
			$this->clientRoutes,
			$this->showRoutes,
			$this->txnRoutes,
			$this->userRoutes,
			$this->reportRoutes
		];

	}

	protected function writeEntityRoutes(): void {
		$this->entityRoutes = [ ...$this->basicRoutes, ...$this->statusRoutes ];
	}

	protected function writeClientRoutes(): void {
		$this->clientRoutes = [
			'handlerClass' => ClientController::class,
			'base'         => 'clients',
			'routes'       => $this->entityRoutes
		];
	}

	protected function writeShowRoutes(): void {
		$this->showRoutes = [
			'handlerClass' => ShowController::class,
			'base'         => 'shows',
			'routes'       => [
				...$this->entityRoutes,
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
	}

	protected function writeTxnRoutes(): void {
		$this->txnRoutes = [
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
					'path'    => 'update',
					'methods' => [
						[
							'methods'             => 'POST',
							'callback'            => 'update',
							'permission_callback' => 'isLoggedIn'
						]
					]
				],
				...$this->statusRoutes,
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
				[
					'path'    => 'receiving/pod',
					'methods' => [
						[
							'methods'             => 'POST',
							'callback'            => 'printPOD',
							'permission_callback' => 'isInternal'
						]
					]
				],
			]
		];
	}

	protected function writeUserRoutes(): void {
		$this->userRoutes = [
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
				...$this->statusRoutes,
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
	}

	protected function writeReportRoutes(): void {
		$this->reportRoutes = [
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
	}
}
