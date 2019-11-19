<?php

return [
'default' => env('DB_CONNECTION', 'sqlite'),
'migrations' => 'migrations',
'connections' => [
     'testing' => [
         'driver'   => 'sqlite',
         'database' => env('DB_DATABASE', database_path('test_database.sqlite')),
         'prefix'   => env('DB_PREFIX', '')
     ],
     'dev' => [
        'driver'   => 'sqlite',
        'database' => env('DB_DATABASE', database_path('database.sqlite')),
        'prefix'   => env('DB_PREFIX', '')
     ],
     'production' => [
        'driver'   => 'pgsql',
        'host'     => env('DB_HOST', '127.0.0.1'),
        'port'     => env('DB_PORT', '5432'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
        'prefix'   => env('DB_PREFIX', '')
     ]
 ]
];