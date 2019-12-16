<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;

class DatabaseTest extends TestCase
{
    use DatabaseMigrations;

    public function testAddDomain()
    {
        $this->call('POST', '/domains', ['url' => 'https://lumen.laravel.com']);

        $this->seeInDatabase('domains', ['name' => 'https://lumen.laravel.com']);
    }

    public function testDomainsPage()
    {
        $response = $this->call('GET', '/domains');
        $this->assertEquals(200, $response->status());
        $this->assertStringContainsString('<html>', $response->getContent());
    }

    public function testAdditionalData()
    {
        $status = 200;
        $headers = ['Content-Length' => 666];
        $body = 'Awesome!';
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
                                         'body' => 'Awesome!',
                                         'record_state' => 'complete']);
    }
}
