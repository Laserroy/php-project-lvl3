<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\DB;

class DomainControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->body = file_get_contents('tests/Fixtures/sample.html');
        $status = 200;
        $headers = ['Content-Length' => 666];
        $mock = new MockHandler([
            new Response($status, $headers, $this->body),
            new RequestException('Error Communicating with Server', new Request('GET', 'test'))
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        
        $this->app->instance(Client::class, $client);
    }


    public function testStore()
    {
        $response = $this->post(route('domains.store'), ['url' => 'https://example.com']);
        $response->assertResponseStatus(200);
        $this->seeInDatabase('domains', [
                                            'name' => 'https://example.com',
                                            'content_length' => 666,
                                            'status' => 200,
                                            'body' => $this->body,
                                            'keywords' => 'keyword',
                                            'header1' => 'Header'
                                        ]);

        $response2 = $this->post(route('domains.store'), ['url' => 'https://error.com']);
        $response2->assertResponseStatus(200);
        $this->seeInDatabase('domains', [
                                            'name' => 'https://error.com',
                                            'record_state' => 'fail'
                                        ]);
    }

    public function testShow()
    {
        $domainId = DB::table('domains')->insertGetId(['name' => 'https://example.com', 'record_state' => 'complete']);
        $response = $this->get(route('domains.show', ['id' => $domainId]));
        $response->assertResponseStatus(200);
    }

    public function testIndex()
    {
        DB::table('domains')->insert([
            ['name' => 'https://example.com', 'record_state' => 'complete'],
            ['name' => 'https://example2.com', 'record_state' => 'complete'],
            ['name' => 'https://example3.com', 'record_state' => 'complete'],
            ['name' => 'https://example4.com', 'record_state' => 'complete'],
            ['name' => 'https://example5.com', 'record_state' => 'complete']
        ]);
        
        $response = $this->get(route('domains.index'));
        $response->assertResponseStatus(200);
    }
}
