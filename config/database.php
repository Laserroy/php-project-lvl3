<?php
$url = parse_url(getenv('DATABASE_URL'));
return [
'default' => env('DB_CONNECTION', 'dev'),
'migrations' => 'migrations',
'connections' => [
     'testing' => [
         'driver'   => 'sqlite',
         'database' => env('DB_DATABASE', database_path('test_database.sqlite')),
     ],
     'development' => [
        'driver'   => 'sqlite',
        'database' => env('DB_DATABASE', database_path('database.sqlite')),
     ],
     'production' => [
        'driver' => 'pgsql',
        'host' => $url["host"] ?? null,
        'port' => $url["port"] ?? null,
        'database' => ltrim($url["path"], "/") ?? null,
        'username' => $url["user"] ?? null,
        'password' => $url["pass"] ?? null,
        'charset' => 'utf8',
        'prefix' => '',
        'schema' => 'public',
        'sslmode' => 'require',
     ]
 ]
];
