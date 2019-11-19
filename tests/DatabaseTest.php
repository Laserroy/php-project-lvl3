<?php
use Laravel\Lumen\Testing\DatabaseMigrations;


class DatabaseTest extends TestCase
{
    use DatabaseMigrations;

    public function testAddDomain()
    {
        $this->call('POST', '/domains', ['url' => 'www.google.com']);

        $this->seeInDatabase('domains', ['name' => 'www.google.com']);
    }
}
