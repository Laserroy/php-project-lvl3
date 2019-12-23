<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;

class DomainControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testStore()
    {
        $this->expectsJobs('App\Jobs\CollectAdditionalData');
        $this->post(route('domains.store'), ['url' => 'https://lumen.laravel.com']);
        $this->seeInDatabase('domains', ['name' => 'https://lumen.laravel.com']);
    }

    public function testShow()
    {
        $this->post('/domains', ['url' => 'https://lumen.laravel.com']);
        $response = $this->call('GET', route('domains.show', ['id' => 1]));
        $this->assertStringContainsString('https://lumen.laravel.com', $response->getContent());
    }

    public function testIndex()
    {
        $response = $this->call('GET', route('domains.index'));
        $this->assertStringContainsString('</table>', $response->getContent());
    }
}
