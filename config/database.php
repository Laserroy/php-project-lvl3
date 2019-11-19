<?php
$DATABASE_URL = parse_url(getenv("DATABASE_URL"));
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
        'driver' => 'pgsql',
        'host' => $DATABASE_URL["host"],
        'port' => $DATABASE_URL["port"],
        'database' => ltrim($DATABASE_URL["path"], "/"),
        'username' => $DATABASE_URL["user"],
        'password' => $DATABASE_URL["pass"],
        'charset' => 'utf8',
        'prefix' => '',
        'schema' => 'public',
        'sslmode' => 'require',
     ]
 ]
];