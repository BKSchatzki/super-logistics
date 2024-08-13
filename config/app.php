<?php

return [
    'name'        => 'Super Logistics',
    'slug'        => 'sl',
    'version'     => '1.0.1',
    'api'     	  => '1',
    'db_version'  => '2.5',
    'text_domain' => 'pm',
    'comment_per_page' => 200,
    'allowed_html' => [
        'a'      => [ 'href' => [], 'title' => [] ],
        'br'     => [],
        'em'     => [],
        'strong' => [],
        'span'   => [
            'style'           => [],
            'class'           => [],
            'id'              => [],
            'data-pm-user-id' => [],
            'data-pm-user'    => [],
            'name'            => [],
            'title'           => []
        ],
        'b'      => [],
        'em'     => [],
        'p'      => [],
        'code'   => [],
        'pre'    => [],
    ]
];
