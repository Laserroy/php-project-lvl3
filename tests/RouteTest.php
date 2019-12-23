<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;

class RouteTest extends TestCase
{
    use DatabaseMigrations;

    public function testApplicationRoutes()
    {
        $response = $this->call('GET', '/');
        $this->assertEquals(200, $response->status());
        
        $response2 = $this->call('GET', '/domains');
        $this->assertEquals(200, $response2->status());

        $response3 = $this->call('POST', '/domains', ['url' => 'https://stackoverflow.com/']);
        $this->assertEquals(302, $response3->status());

        $response4 = $this->call('GET', '/domains/1');
        $this->assertEquals(200, $response4->status());
    }
}
