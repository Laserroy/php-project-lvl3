<?php

namespace Tests;

use Tests\TestCase;
use Laravel\Lumen\Testing\DatabaseMigrations;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use App\Domain;

class DomainControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testStore()
    {
        $testBody = file_get_contents('tests/Fixtures/sample.html');
        $domain = factory(Domain::class)
                                ->make([
                                    'body' => $testBody,
                                    'state' => 'completed',
                                    ]);
        $headers = ['Content-Length' => $domain->content_length];
        $mock = new MockHandler([
            new Response($domain->status, $headers, $domain->body)
        ]);
        $this->registerFakeClientMock($mock);
            
        $this->post(route('domains.store'), ['url' => $domain->name]);
        $this->assertResponseStatus(302);
        $this->seeInDatabase('domains', [
                                            'name' => $domain->name,
                                            'content_length' => $domain->content_length,
                                            'status' => $domain->status,
                                            'body' => $domain->body,
                                            'state' => $domain->state,
                                            'header1' => $domain->header1,
                                            'description' => $domain->description,
                                            'keywords' => $domain->keywords
                                        ]);
    }

    public function testStoreDomainWithError()
    {
        $mock = new MockHandler([
            new RequestException('Error Communicating with Server', new Request('GET', 'test'))
        ]);
        $this->registerFakeClientMock($mock);
        
        $domain = factory(Domain::class)->make(['name' => 'https://error.com']);
        $this->post(route('domains.store'), ['url' => $domain->name]);
        $this->assertResponseStatus(302);
        $this->seeInDatabase('domains', [
            'name' => $domain->name,
            'state' => 'failed'
        ]);
    }

    public function testShow()
    {
        $domain = factory(Domain::class)->create();
        
        $response = $this->get(route('domains.show', ['id' => $domain->id]));
        $response->assertResponseStatus(200);
    }

    public function testIndex()
    {
        factory(Domain::class, 10)->create();
        
        $this->get(route('domains.index'));
        $this->assertResponseStatus(200);
    }

    public function registerFakeClientMock($mock)
    {
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $client);
    }
}
