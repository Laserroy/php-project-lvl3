<?php
return [
    'domain_graph' => [
        'class' => App\Domain::class,
        'graph' => 'domain_graph',
        'property_path' => 'state',
        'metadata' => [
            'title' => 'domain_graph',
        ],
        'states' => [
            'initiated',
            'completed',
            'failed',
        ],
        'transitions' => [
            'processed' => [
                'from' => ['initiated'],
                'to' => 'completed',
            ],
            'errored' => [
                'from' => ['initiated'],
                'to' => 'failed',
            ],
        ],
    ],
];
