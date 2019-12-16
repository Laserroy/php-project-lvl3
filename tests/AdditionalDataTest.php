<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class DatabaseTest extends TestCase
{
    use DatabaseMigrations;

    public function testAdditionalDomainData()
    {
        $status = 200;
        $headers = ['Content-Length' => 666];
        $body = '<h1>Awesome!</h1>';
        $mock = new MockHandler([
            new Response($status, $headers, $body)
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        
        $this->app->instance(Client::class, $client);

        $this->call('POST', '/domains', ['url' => 'https://stackoverflow.com/']);
        $this->seeInDatabase('domains', ['name' => 'https://stackoverflow.com/',
                                         'content_length' => 666,
                                         'status' => 200,
                                         'body' => $body,
                                         'header1' => 'Awesome!',
                                         'record_state' => 'complete']);
    }
}
