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
            'process' => [
                'from' => ['initiated', 'failed'],
                'to' => 'completed',
            ],
            'reject' => [
                'from' => ['initiated'],
                'to' => 'failed',
            ],
        ],
    ],
];
