<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;

class RoutesTest extends TestCase
{
    use DatabaseMigrations;

    public function testMainPageRoute()
    {
        $response = $this->call('GET', '/');

        $this->assertEquals(200, $response->status());
        $this->assertStringContainsString('<html>', $response->getContent());
    }
    
    public function testDomainAdding()
    {
        $this->call('POST', '/domains', ['url' => 'https://lumen.laravel.com']);

        $this->seeInDatabase('domains', ['name' => 'https://lumen.laravel.com']);
    }

    public function testDomainsPage()
    {
        $response = $this->call('GET', '/domains');
        $this->assertEquals(200, $response->status());
        $this->assertStringContainsString('</table>', $response->getContent());
    }

    public function testDomainIdRoute()
    {
        $this->call('POST', '/domains', ['url' => 'https://lumen.laravel.com']);
        $response = $this->call('GET', '/domains/1');
        $this->assertStringContainsString('https://lumen.laravel.com', $response->getContent());
    }
}
